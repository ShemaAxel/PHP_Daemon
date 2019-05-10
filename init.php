#!/usr/bin/php
<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once 'System/Daemon.php';

use App\Config;
use App\Helper;
use App\Processor;

date_default_timezone_set("Africa/Kigali");

$configs = Config::instantiate();
$helpers = Helper::instantiate();

System_Daemon::setOptions($configs->getOptions());
System_Daemon::start();

$service = new Processor();

while (!System_Daemon::isDying()) {
    try {
        $helpers->logHelper('Daemon processing about to start');
        $service->start();
    } catch (PDOException $ex) {
        $helpers->logHelper("PDO Exception: " . $ex->getMessage());
    } catch (Exception $ex) {
        $helpers->logHelper("Application Exception " . $ex->getMessage());
    }

    System_Daemon::iterate(3);
}
System_Daemon::stop();

?>