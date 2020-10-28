<?php
require 'vendor/autoload.php';
use AfricasTalking\SDK\AfricasTalking;
$client = new \GuzzleHttp\Client();
use GuzzleHttp\Psr7\Request;

$xml = '<?xml version="1.0" encoding="UTF-8"?>
        <request type="sendsms">
        <authentication>
            <username>ashema</username>
            <password>$2y$10$n0.Q7/HEfEf82.kvH2CGguvON0HhCATSsEzjixM9Kon.u6qCKIyKK</password>
            <key>20140812</key>
        </authentication>
        <parameters>
            <dlr>1</dlr>
            <recipient>250782980090</recipient>
            <sender>RVCP</sender>
            <message>Test message</message>
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
