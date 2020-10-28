<?php
require 'vendor/autoload.php';
use AfricasTalking\SDK\AfricasTalking;

$username = "axel";
$apiKey = "412022e608798a4a37ca34d9bfe7f46a673da3be48c9d80ba3c8417d5ff9da48";
$AT = new AfricasTalking($username, $apiKey);
$sms = $AT->sms();
$recipients = "+250782980090";
$penalty = 118;
$totalAmount = 15986;
// Set your message
$message = "Mukiriya wacu turabamenyeshako ukwezi ko kwishyura kwarenze.\n"
    . "hiyongereyeho " . $penalty . "RWF yubukererwe,"
    . "mukazishyura " . $totalAmount . "RWF.Murakoze"; //message
// Set your shortCode or senderId
$from = "churchService";
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
