<?php
namespace App;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use ORM;

use App\Config;


class Helper {

    /**
     * Singleton instance of the class
     */
    public static $singleton = NULL;
    

    private $logger = NULL;
    private $configs;

    public function __construct() {
        $this->configs = Config::instantiate();
        $this->setLogger();
    }

    /**
     * Application logging handling
     * 
     */
    public function setLogger() {
        $log = new Logger('momocanal_service_logger');
        $log->pushHandler(new StreamHandler($this->configs->getDebugLogFile(),  Logger::DEBUG));
        $log->pushHandler(new StreamHandler($this->configs->getInfoLogFile(),  Logger::INFO));
        $log->pushHandler(new StreamHandler($this->configs->getErrorLogFile(),  Logger::ERROR));
        $log->pushHandler(new StreamHandler($this->configs->getFatalLogFile(),  Logger::CRITICAL));      
        $this->logger = $log;
      }
      
      /*
       * Database connection
       */
    public function connect() {
        ORM::configure($this->configs->getDbLink());
        ORM::configure('username', $this->configs->getDbUser());
        ORM::configure('password', $this->configs->getDbPass());
        ORM::configure('logging', $this->configs->getDbLog());
        ORM::configure("return_result_set", $this->configs->getDbResultSets());

        ORM::configure('id_column_overrides', array(
            'canal_invoices' => 'invoiceID',
            'canalMomoPayments' => 'momoPaymentID',
            'payments' => 'paymentID',
            'outboundSMS' => 'outboundSMSID',
            'requestLogs' => 'requestLogID'
        ));
    }

    /**configs
     * date helper function
     * @param timestamp
     * @return string
     */
    public function dateHelper($time = NULL) {
        $date = ($time === NULL)? getdate() : getdate($time);
        $date1 = $date["year"] . '-' . $this->dateStringHelper($date["mon"]) . '-' . $this->dateStringHelper($date["mday"]) . ' ' . $this->dateStringHelper($date["hours"]) . ':' . $this->dateStringHelper($date["minutes"]) . ':' . $this->dateStringHelper($date["seconds"]);
        return $date1;
    }
    public function dateStringHelper($v) {
        if ($v <= 9)
        return '0'. $v;
        else
        return $v;
    }

    /**
     * 
     * fills a template message from db with values
     * where placeholder is ^name^, but it can be anything
     * 
     * @param array format ["^name^" => "value"]
     * @return string
     * 
     */

    public function formatMessage($content = [], $subject) {
        $template = ORM::for_table('templates')
            ->where([
                'subject' => $subject,
                'status' => 1
            ])->find_one();

        return str_replace(\array_keys($content), \array_values($content), $template->content);
        
    }

/**
     * returns a valide phone number that can de sent to equity api
     * @param string unformatted phone number
     * @return string formatted phone number
     */
    public function validatePhoneNumber($phone) {
        if (strlen($phone) > 10) {
            $phone_array = str_split($phone);
            if(count($phone_array) > 10) {
                $diff = count($phone_array) - 10;
                for($i = 0; $i < $diff; $i++) {
                    unset($phone_array[$i]);
                }
            }
            $phone = implode($phone_array);
        }
        if (strlen($phone) == 9) {
            $phone = '0' . $phone;
        }

        if (strlen($phone) == 10) {
            if (strncasecmp($phone, "078", 3) === 0 || strncasecmp($phone, "073", 3) === 0 || strncasecmp($phone, "072", 3) === 0) {
                return $phone;
            } else {
                return "0788000000";
            }
        } else {
            return "0788000000";
        }
    }





    /**
     * return an internationally formatted phone number
     * @param string $phone the formatted or unformatted phone number
     * @return string
     */

    public function formatPhoneNumber($MSISDN, $internationalMode = false) {
        $formattedMSISDN = NULL;
        //Get the international country code
        $countryCode = $this->configs->getCountryCode();
        

        //Sanitize the phone number || Strip non digits
        $formattedMSISDN = preg_replace('/[^0-9\s]/', "", $MSISDN);

        //If international format, strip the leading 0
        if (substr($formattedMSISDN, 0, 1) == 0 && strlen($formattedMSISDN) == 10) {
            $formattedMSISDN = substr_replace($formattedMSISDN, "", 0, 1);
        }
        
        if(strlen($formattedMSISDN) <= 9 && strlen($formattedMSISDN) > 0) {
            $formattedMSISDN = $countryCode  . $formattedMSISDN;
        }
        
        if($internationalMode) {
            $formattedMSISDN = '+' . $formattedMSISDN;
        }

        //FormattedMSISDN
        return $formattedMSISDN;
    }

    public function logHelper($message, $level = "info") {
        if ($this->configs->getLogDestination() == "CONSOLE") {
            echo $message . "\n";
        } else {
            if ($this->logger == NULL) {
                return;
            }
            switch($level) {
                case "info":
                    $this->logger->info($message);
                    break;
                case "debug":
                    $this->logger->debug($message);
                    break;
                case "error":
                    $this->logger->error($message);
                    break;
                case "critical":
                    $this->logger->critical($message);
                    break;
            }
        }
    }


    public static function instantiate(){
        if(is_null(self::$singleton)) {
            self::$singleton = new Helper();
        }
        return self::$singleton;
    }


    /**
     * Get the value of configs
     */ 
    public function getConfigs()
    {
        return $this->configs;
    }

    /**
     * Get the value of logger
     */ 
    public function getLogger()
    {
        return $this->logger;
    }
}

