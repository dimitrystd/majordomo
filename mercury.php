<?php

// https://github.com/mrkrasser/MercuryStats/blob/master/Readme.ru.md
// https://github.com/mrkrasser/MercuryStats/blob/master/examples/MercuryStatsGetCurrent.xively.php

// ini_set('max_execution_time', 10);

// Parameters for port
exec('/bin/stty -F /dev/ttyUSB0 9600 ignbrk -brkint -icrnl -imaxbel -opost -onlcr -isig -icanon -iexten -echo -echoe -echok -echoctl -echoke noflsh -ixon -crtscts');

// Open port
$fp = fopen('/dev/ttyUSB0', 'r+');

if (!$fp) {
  echo 'Error1';
  exit;
}

// Sent command to device
stream_set_blocking($fp, 1);

fwrite($fp, "\x00\x0D\x29\x0F\x63\xb2\xbd"); // string to receiving current amperage,voltage with corrected CRC and device address

// Read answer from device with 500ms timeout
$result = '';
$c = '';
stream_set_blocking($fp, 0);
$timeout = microtime(1) + 0.5;

while (microtime(1) < $timeout) {
  $c = fgetc($fp);
  if ($c === false) {
    usleep(5);
    continue;
  }

  $result.= $c;
}

fclose($fp);

// split answer data on parts
// $crc = substr($result, -2); // crc16  of answer
// $addr = hexdec(bin2hex(substr($result, 1, 3))); // address of power device
// $answer_cmd = substr($result, 4, -2); // answered command
$answer = substr($result, 5, -2); // answer string

// Format and output data
$voltage = bin2hex(substr($answer, 0, 2)) / 10;
$amperage = bin2hex(substr($answer, 2, 2)) / 100;
$energy = bin2hex(substr($answer, 4, 3)) / 1000;

echo "<br>Напряжение сети:  $voltage Uv\n";
echo "<br>Сила така :  $amperage Ia\n";
echo "<br>Потребляемая мощьность : $energy P kVt";
		
