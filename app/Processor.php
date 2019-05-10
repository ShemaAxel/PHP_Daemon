<?php

namespace App;

use AfricasTalking\SDK\AfricasTalking;
use App\Config;
use App\Helper;
use ORM;

class Processor
{
    private $configs;
    private $helpers;

    public function __construct()
    {
        $this->configs = Config::instantiate();
        $this->helpers = Helper::instantiate();
        $this->helpers->connect();

    }
    public function start()
    {

        // check for pending momo payments
        $this->helpers->logHelper('checking for pending messages');
        $size = $this->countPendingMessage();
        $this->helpers->logHelper('Found ' . $size . ' pending sms');

        if ($size > 0) {
            ORM::get_db()->beginTransaction();
            // fetch pending momo payments
            $this->helpers->logHelper('fetching pending sms');
            $pendingMessages = $this->fetchPendingMessage();
            $this->helpers->logHelper('payments: ' . json_encode($pendingMessages), 'debug');

            foreach ($pendingMessages as $pendingMessage) {
                $this->helpers->logHelper('About to update message ID:' . $pendingMessage->outboundSMSID);
                if ($this->sendMessage($pendingMessage->outboundSMSID)) {
                    ORM::get_db()->commit();
                } else {
                    // Roll back a transaction
                    ORM::get_db()->rollBack();
                }

            }
        }
        //Thread sleep time
        sleep(60);

    }

    public function updateMessage($id)
    {
        try {
            $this->helpers->logHelper("Searching for message ID:" . $id);
            $sms = ORM::for_table('outboundSMS')->find_one($id);
            $sms->status = 1;
            $sms->save();
            $this->helpers->logHelper("message updated successful.");
            return $sms->id();
        } catch (PDOException $e) {
            $this->helpers->logHelper("message update error: " . $e->getMessage(), "critical");
            return false;
        } catch (Exception $e) {
            $this->helpers->logHelper("message update error: " . $e->getMessage(), "critical");
            return false;
        }
    }

    public function sendMessage($id)
    {
        try {
            $this->helpers->logHelper("Sending message ID:" . $id);
            $outboundSMS = ORM::for_table('outboundSMS')->find_one($id);
            //Af instanciate
            $AT = new AfricasTalking($this->configs->getUserName(), $this->configs->getApiKey());
            $sms = $AT->sms();
            $from = null;
            // Thats it, hit send and we'll take care of the rest
            $result = $sms->send([
                'to' => $outboundSMS->MSISDN,
                'message' => $outboundSMS->message,
                'from' => $from,
            ]);
            $this->helpers->logHelper("message sent successful.");
            $outboundSMS->status = 1;
            $outboundSMS->save();
            return true;
        } catch (PDOException $e) {
            $this->helpers->logHelper("message sent error: " . $e->getMessage(), "critical");
            return false;
        } catch (Exception $e) {
            $this->helpers->logHelper("message sent error: " . $e->getMessage(), "critical");
            return false;
        } catch (AfricasTalkingGatewayException $e) {
            $this->helpers->logHelper("Sending sms failed: " . $e->getMessage(), "critical");
            $outboundSMS = ORM::for_table('outboundSMS')->find_one($id);
            $outboundSMS->status = 3;
            $outboundSMS->save();
            return true;
        }

    }

    public function countPendingMessage()
    {
        $size = ORM::for_table('outboundSMS')
            ->where('status', 0)
            ->count();

        return $size;
    }

    public function fetchPendingMessage()
    {
        $pendingMomo = ORM::for_table('outboundSMS')
            ->where('status', 0)
            ->limit($this->configs->getBucketSize())
            ->find_many();

        return $pendingMomo;
    }
    /**
     * @param payment db object
     * @return boolean
     */

}
