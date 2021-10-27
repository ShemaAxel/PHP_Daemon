<?php
require 'vendor/autoload.php';
use AfricasTalking\SDK\AfricasTalking;

$username = "";
$apiKey = "";
$AT = new AfricasTalking($username, $apiKey);
$sms = $AT->sms();
$recipients = "";
$penalty = ;
$totalAmount = ;
// Set your message
$message = ""; //message
// Set your shortCode or senderId
$from = "";
try {
    // Thats it, hit send and we'll take care of the rest
    $result = $sms->send([
        'to' => $recipients,
        'message' => $message,
        'from' => $from,
    ]);

    print_r($result);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
