<?php
/*
* Снятие показания с счётчика Меркурий 200
* https://github.com/mrkrasser/MercuryStats/blob/master/Readme.ru.md
* https://github.com/mrkrasser/MercuryStats/blob/master/examples/MercuryStatsGetCurrent.xively.php
*/

chdir(dirname(__FILE__).'/../');

include_once("./config.php");
include_once ROOT . './lib/newrelic/newrelic.inc.php';
include_once("./lib/loader.php");
include_once("./lib/threads.php");

set_time_limit(0);

// connecting to database
$db = new mysql(DB_HOST, '', DB_USER, DB_PASSWORD, DB_NAME);

include_once("./load_settings.php");
require_once("./mercury.php");

$checked_time=0;

echo date("H:i:s") . " running " . basename(__FILE__) . "\n";
$log = getLogger(__FILE__);

while(1)
{
  if (time()-$checked_time>20) {
    $data = getDataFromMercury();
    setGlobal('Mercury.Uv', $data['ac_v']);
    setGlobal('Mercury.Ia', $data['ac_i']);
    setGlobal('Mercury.Pv', $data['ac_p']);
    $log->trace("Immediate values of V = ${data['ac_v']}, I = ${data['ac_i']}, P = ${data['ac_p']}");
    $checked_time=time();
    setGlobal((str_replace('.php', '', basename(__FILE__))).'Run', time(), 1);
  }


  if (file_exists('./reboot') || $_GET['onetime'])
  {
    $db->Disconnect();
    exit;
  }

  sleep(1);
}

DebMes('Unexpected close of cycle: ' . basename(__FILE__));
$log->error('Unexpected close of cycle: ' . basename(__FILE__));
