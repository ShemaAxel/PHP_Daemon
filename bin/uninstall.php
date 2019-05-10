#!/usr/bin/php
<?php

require_once(__DIR__ . '/../vendor/autoload.php');


//Get an instance of options to pass to determine runtime
$configuration = \App\Config::instantiate();
$options = $configuration->getOptions();

unlink("/etc/init.d/" . $options['appName']);

?>