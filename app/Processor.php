<?php

namespace App;

use AfricasTalking\SDK\AfricasTalking;
use App\Config;
use App\Helper;
use ORM;
use GuzzleHttp\Psr7\Request;

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
            //ORM::get_db()->beginTransaction();
            // fetch pending momo payments
            $this->helpers->logHelper('fetching pending sms');
            $pendingMessages = $this->fetchPendingMessage();
            $this->helpers->logHelper('' . json_encode($pendingMessages), 'debug');

            foreach ($pendingMessages as $pendingMessage) {
                $this->helpers->logHelper('About to update message ID:' . $pendingMessage->outboundSMSID);
                if ($this->sendMessage($pendingMessage->outboundSMSID)) {
                   // ORM::get_db()->commit();
                } else {
                    // Roll back a transaction
                    //ORM::get_db()->rollBack();
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
            $sms = ORM::for_table('outboundsms')->find_one($id);
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
            $outboundSMS = ORM::for_table('outboundsms')->find_one($id);
          
            $xml = '<?xml version="1.0" encoding="UTF-8"?>
            <request type="sendsms">
            <authentication>
                <username>ashema</username>
                <password>$2y$10$n0.Q7/HEfEf82.kvH2CGguvON0HhCATSsEzjixM9Kon.u6qCKIyKK</password>
                <key>20140812</key>
            </authentication>
            <parameters>
                <dlr>1</dlr>
                <recipient>'.$outboundSMS->MSISDN.'</recipient>
                <sender>RVCP</sender>
                <message>'.$outboundSMS->message.'</message>
            </parameters>
            </request>';
          
          
            $uri ='https://qa.dsmsystem.biz/dsm_pdn/v1/smscenter/api';

            $request = new Request(
                'POST', 
                $uri,
                ['Content-Type' => 'text/xml; charset=UTF8'],
                $xml
            );
            $response = $client->send($request);
          
            $this->helpers->logHelper("Response: ".$response->getBody());
  
            $this->helpers->logHelper("message sent successful.");
            $outboundSMS->status = 1;
            $outboundSMS->save();
            $this->pdo->commit();

            return true;
        } catch (PDOException $e) {
            $this->helpers->logHelper("message sent error: " . $e->getMessage(), "critical");
            return false;
        } catch (Exception $e) {
            $this->helpers->logHelper("message sent error: " . $e->getMessage(), "critical");
            return false;
        }

    }

    public function countPendingMessage()
    {
        $size = ORM::for_table('outboundsms')
            ->where('status', 0)
            ->count();

        return $size;
    }

    public function fetchPendingMessage()
    {
        $pendingMomo = ORM::for_table('outboundsms')
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
