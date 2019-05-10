#!/usr/bin/php
<?php

require_once(__DIR__ . '/../vendor/autoload.php');
require_once("System/Daemon.php");


//Get an instance of options to pass to determine runtime
$configuration = \App\Config::instantiate();
$options = $configuration->getOptions();


System_Daemon::setOptions($options);
System_Daemon::writeAutoRun();


?>