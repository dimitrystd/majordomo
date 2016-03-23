<?php
/**
* Project Config
*
* @package MajorDoMo
* @author Serge Dzheigalo <jey@tut.by> http://smartliving.ru/
* @version 1.1
*/

 Define('TIME_ZONE', "Europe/Kiev");

 Define('DB_HOST', $_ENV['DB_HOST']);
 Define('DB_NAME', $_ENV['DB_NAME']);
 Define('DB_USER', $_ENV['DB_USER']);
 Define('DB_PASSWORD', $_ENV['DB_PASSWORD']);

 Define('GMAIL_USER', $_ENV['MAIL_USER']);
 Define('GMAIL_PASSWORD', $_ENV['EMAIL_PASSWORD']);


 Define('DIR_TEMPLATES', "./templates/");
 Define('DIR_MODULES', "./modules/");
 Define('DEBUG_MODE', 1);
 Define('UPDATES_REPOSITORY_NAME', 'smarthome');

 Define('PROJECT_TITLE', 'MajordomoSL');
 Define('PROJECT_BUGTRACK', "bugtrack@smartliving.ru");

error_reporting(E_ALL ^ E_NOTICE);

 if ($_ENV['COMPUTERNAME']) {
  Define('COMPUTER_NAME', strtolower($_ENV['COMPUTERNAME']));
 } else {
  Define('COMPUTER_NAME', 'mycomp');                       // Your computer name (optional)
 }


 Define('DOC_ROOT', dirname(__FILE__));              // Your htdocs location (should be detected automatically)

 Define('SERVER_ROOT', '/var/www/html');


 if ($_ENV["S2G_BASE_URL"]) {
  Define('BASE_URL', $_ENV["S2G_BASE_URL"]);
 } else {
  Define('BASE_URL', 'http://127.0.0.1:80');              // Your base URL:port (!!!)
 }


 Define('ROOT', DOC_ROOT."/");
 Define('ROOTHTML', "/");
 Define('PROJECT_DOMAIN', $_SERVER['SERVER_NAME']);

 //Define('ONEWIRE_SERVER', 'tcp://localhost:8234');    // 1-wire OWFS server


 Define('HOME_NETWORK', '192.168.99.*');                  // home network (optional)
 Define('EXT_ACCESS_USERNAME', $_SERVER['EXT_ACCESS_USERNAME']);  // access details for external network (internet)
 Define('EXT_ACCESS_PASSWORD', $_SERVER['EXT_ACCESS_PASSWORD']);


 //Define('DROPBOX_SHOPPING_LIST', 'c:/data/dropbox/list.txt');  // (Optional)
 const MAJORDOMO_GITHUB_URL = 'https://github.com/sergejey/majordomo';
 const WINDOWS_PHP_PATH = 'C:\wamp\bin\php\php5.4.16\php.exe';

 $restart_threads=array(
                       'cycle_execs.php',
                       'cycle_main.php',
                       'cycle_ping.php',
                       'cycle_scheduler.php',
                       'cycle_states.php',
                       'cycle_webvars.php',
                       'cycle_mercurypower.php');