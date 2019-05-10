<?php

namespace App;

class Config
{

    public static $singleton;

    #Change between Dev Environment and Production Environment
    private $env;
    private $logDestination;

    #Database connection data
    private $dbLink;
    private $dbUser;
    private $dbPass;
    private $dbLog;
    private $dbResultSets;

    #Log files
    private $infoLogFile;
    private $errorLogFile;
    private $debugLogFile;
    private $fatalLogFile;

    private $newInvoiceStatus;
    private $incompletePaymentStatus;
    private $unprocessedStatus;

    private $bucketSize;
    private $countryCode;

    private $serviceID;
    #Africastalking
    private $userName;
    private $apiKey;

    /**
     * Daemon settings
     * please do not edit
     */
    private $myOptions = [
        "authorName" => 'Shema Romeo Axel',
        "authorEmail" => 'shemaromeoaxel@gmail.com',
        "appName" => 'smssender',
        "appDescription" => 'updating sms status',
        "appDir" => __DIR__ . '/..',
        "appExecutable" => 'init.php',
        "logPhpErrors" => "TRUE",
        "logFilePosition" => "TRUE",
        "logLinePosition" => "TRUE",
        "sysMaxExecutionTime" => "0",
        "sysMaxInputTime" => "0",
        "sysMemoryLimit" => "512M",
    ];

    public function __construct()
    {
        //Initialize our application environment variables
        $env = \Dotenv\Dotenv::create(__DIR__ . '/..');
        $env->load();

        $this->env = getenv('ENV');
        $this->logDestination = getenv('LOG_DESTINATION');
        $this->dbLink = getenv('DSN');
        $this->dbUser = getenv('DB_USER');
        $this->dbPass = getenv('DB_PASS');
        $this->dbResultSets = getenv('DB_RESULT_SETS');
        $this->dbLog = getenv('DB_LOG');
        $this->infoLogFile = getenv('LOG_LOCATION') . getenv('INFO_LOG_FILE');
        $this->errorLogFile = getenv('LOG_LOCATION') . getenv('ERROR_LOG_FILE');
        $this->debugLogFile = getenv('LOG_LOCATION') . getenv('DEBUG_LOG_FILE');
        $this->fatalLogFile = getenv('LOG_LOCATION') . getenv('FATAL_LOG_FILE');
        $this->newInvoiceStatus = getenv('STATUS_NEW_INVOICE');
        $this->incompletePaymentStatus = getenv('STATUS_INCOMPLETE_PAYMENT');
        $this->unprocessedStatus = getenv('STATUS_UNPROCESSED');
        $this->bucketSize = getenv('BUCKET_SIZE');
        $this->countryCode = getenv('COUNTRY_CODE');
        $this->serviceID = getenv('SERVICE_ID');
        $this->userName = getenv('AF_USERNAME');
        $this->apiKey = getenv('AF_API_KEY');
    }

    public static function instantiate()
    {
        if (is_null(self::$singleton)) {
            self::$singleton = new Config();
        }
        return self::$singleton;
    }

    //getters and setters
    /**
     * Get the value of Africatalking User
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * Set the value of Africatalking User
     *
     * @return  self
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
        return $this;
    }
    /**
     * Get the value of Africatalking Api Key
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Set the value of Africatalking Api Key
     *
     * @return  self
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * Get the value of env
     */
    public function getEnv()
    {
        return $this->env;
    }

    /**
     * Set the value of env
     *
     * @return  self
     */
    public function setEnv($env)
    {
        $this->env = $env;

        return $this;
    }

    /**
     * Get the value of logDestination
     */
    public function getLogDestination()
    {
        return $this->logDestination;
    }

    /**
     * Set the value of logDestination
     *
     * @return  self
     */
    public function setLogDestination($logDestination)
    {
        $this->logDestination = $logDestination;

        return $this;
    }

    /**
     * Get the value of dbLink
     */
    public function getDbLink()
    {
        return $this->dbLink;
    }

    /**
     * Set the value of dbLink
     *
     * @return  self
     */
    public function setDbLink($dbLink)
    {
        $this->dbLink = $dbLink;

        return $this;
    }

    /**
     * Get the value of dbUser
     */
    public function getDbUser()
    {
        return $this->dbUser;
    }

    /**
     * Set the value of dbUser
     *
     * @return  self
     */
    public function setDbUser($dbUser)
    {
        $this->dbUser = $dbUser;

        return $this;
    }

    /**
     * Get the value of dbPass
     */
    public function getDbPass()
    {
        return $this->dbPass;
    }

    /**
     * Set the value of dbPass
     *
     * @return  self
     */
    public function setDbPass($dbPass)
    {
        $this->dbPass = $dbPass;

        return $this;
    }

    /**
     * Get the value of dbLog
     */
    public function getDbLog()
    {
        return $this->dbLog;
    }

    /**
     * Set the value of dbLog
     *
     * @return  self
     */
    public function setDbLog($dbLog)
    {
        $this->dbLog = $dbLog;

        return $this;
    }

    /**
     * Get the value of dbResultSets
     */
    public function getDbResultSets()
    {
        return $this->dbResultSets;
    }

    /**
     * Set the value of dbResultSets
     *
     * @return  self
     */
    public function setDbResultSets($dbResultSets)
    {
        $this->dbResultSets = $dbResultSets;

        return $this;
    }

    /**
     * Get the value of infoLogFile
     */
    public function getInfoLogFile()
    {
        return $this->infoLogFile;
    }

    /**
     * Set the value of infoLogFile
     *
     * @return  self
     */
    public function setInfoLogFile($infoLogFile)
    {
        $this->infoLogFile = $infoLogFile;

        return $this;
    }

    /**
     * Get the value of errorLogFile
     */
    public function getErrorLogFile()
    {
        return $this->errorLogFile;
    }

    /**
     * Set the value of errorLogFile
     *
     * @return  self
     */
    public function setErrorLogFile($errorLogFile)
    {
        $this->errorLogFile = $errorLogFile;

        return $this;
    }

    /**
     * Get the value of debugLogFile
     */
    public function getDebugLogFile()
    {
        return $this->debugLogFile;
    }

    /**
     * Set the value of debugLogFile
     *
     * @return  self
     */
    public function setDebugLogFile($debugLogFile)
    {
        $this->debugLogFile = $debugLogFile;

        return $this;
    }

    /**
     * Get the value of fatalLogFile
     */
    public function getFatalLogFile()
    {
        return $this->fatalLogFile;
    }

    /**
     * Set the value of fatalLogFile
     *
     * @return  self
     */
    public function setFatalLogFile($fatalLogFile)
    {
        $this->fatalLogFile = $fatalLogFile;

        return $this;
    }

    /**
     * Get daemon settings
     */
    public function getOptions()
    {
        return $this->myOptions;
    }

    /**
     * Get the value of newInvoiceStatus
     */
    public function getNewInvoiceStatus()
    {
        return $this->newInvoiceStatus;
    }

    /**
     * Set the value of newInvoiceStatus
     *
     * @return  self
     */
    public function setNewInvoiceStatus($newInvoiceStatus)
    {
        $this->newInvoiceStatus = $newInvoiceStatus;

        return $this;
    }

    /**
     * Get the value of incompletePaymentStatus
     */
    public function getIncompletePaymentStatus()
    {
        return $this->incompletePaymentStatus;
    }

    /**
     * Set the value of incompletePaymentStatus
     *
     * @return  self
     */
    public function setIncompletePaymentStatus($incompletePaymentStatus)
    {
        $this->incompletePaymentStatus = $incompletePaymentStatus;

        return $this;
    }

    /**
     * Get the value of unprocessedStatus
     */
    public function getUnprocessedStatus()
    {
        return $this->unprocessedStatus;
    }

    /**
     * Set the value of unprocessedStatus
     *
     * @return  self
     */
    public function setUnprocessedStatus($unprocessedStatus)
    {
        $this->unprocessedStatus = $unprocessedStatus;

        return $this;
    }

    /**
     * Get the value of bucketSize
     */
    public function getBucketSize()
    {
        return $this->bucketSize;
    }

    /**
     * Set the value of bucketSize
     *
     * @return  self
     */
    public function setBucketSize($bucketSize)
    {
        $this->bucketSize = $bucketSize;

        return $this;
    }

    /**
     * Get the value of countryCode
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * Set the value of countryCode
     *
     * @return  self
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * Get the value of serviceID
     */
    public function getServiceID()
    {
        return $this->serviceID;
    }

    /**
     * Set the value of serviceID
     *
     * @return  self
     */
    public function setServiceID($serviceID)
    {
        $this->serviceID = $serviceID;

        return $this;
    }
}
