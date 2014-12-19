<?php
header('Connection: close');
flush();

require_once('config.php');
require_once(ROOT . './lib/loader.php');
global $db;
$db = new mysql(DB_HOST, '', DB_USER, DB_PASSWORD, DB_NAME);

$log = getLogger(__FILE__);

// Try to resolve MegaD object by IP
$ip = $_SERVER['REMOTE_ADDR'];
$log->trace(sprintf('Got message from MegaDevice (ip = %s, params = [%s])', $ip, 
  implode(', ', array_map(function ($v, $k) { return $k . '=' . $v; }, $params, array_keys($params)))));
if (empty($ip)){
  $log->error('Cannot determinate remote IP address of megadevice!');
  exit;
}
$objects = getObjectsByClass('Megadevice');
$megaD = null;
if (!empty($params['mdid'])) {
  foreach ($objects as $obj) {
    if (strnatcasecmp(trim(getGlobal($obj['TITLE'].'.id')), $params['mdid']) == 0) {
      $megaD = $obj;
      break;
    }
  }
}
if (empty($megaD)) {
  foreach ($objects as $obj) {
    if (trim(getGlobal($obj['TITLE'].'.ipAddress')) == $ip) {
      $megaD = $obj;
      break;
    }
  }
}
if (empty($megaD)) {
  $log->error('Cannot find instance of Megadevice class with ip = ' . $ip);
  exit;
}
// Try to find Light objects (by device and input port)
$inputDevice = $megaD['TITLE'];
$inputPort = $params['pt'];
$objects = getObjectsByClass("Light");
$light = null;
foreach ($objects as $obj) {
  if (strnatcasecmp(getGlobal($obj['TITLE'].'.inputDevice'), $inputDevice) == 0 &&
    strnatcasecmp(getGlobal($obj['TITLE'].'.inputPort'), $inputPort) == 0) {
    $light = $obj;
  }
}
if (empty($light)) {
  $log->error(sprintf('Cannot find instance of Light class with inputDevice = %s and inputPort = %d', $inputDevice, $inputPort));
  exit;
}

$log->trace(sprintf('Object %s will process call', $light['TITLE']));
callMethod($light['TITLE'].'.switchPressed');