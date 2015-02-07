<?php
/*
* Цикл контроля подсистемы электропитания Cubietruck
* Оригинальный код http://smartliving.ru/forum/viewtopic.php?f=4&t=1783
*/

chdir(dirname(__FILE__).'/../');

include_once("./config.php");
include_once("./lib/loader.php");

set_time_limit(0);

// connecting to database
$db = new mysql(DB_HOST, '', DB_USER, DB_PASSWORD, DB_NAME);

include_once("./load_settings.php");

$checked_time = 0;

while(1)
{
  echo date("H:i:s") . " running " . basename(__FILE__) . "\n";
  if (time()-$checked_time > 10) {
    $checked_time = time();
    // Контроль параметров батареи
    $bt_p=mb_substr(file_get_contents('/sys/class/power_supply/battery/present'), 0, -1);    // наличие батареи [1 = подключена/ 0 = отключена]
    $bt_o=mb_substr(file_get_contents('/sys/class/power_supply/battery/online'), 0, -1);        // питание от батареи или нет [1/0]
    $bt_s=mb_substr(file_get_contents('/sys/class/power_supply/battery/status'), 0, -1);        // текущий статус батареи [Full/Charge/Discharge]
    $bt_c=mb_substr(file_get_contents('/sys/class/power_supply/battery/capacity'), 0, -1);    // текущая емкость батареи [0-100%]
    $bt_v=round(intval(file_get_contents('/sys/class/power_supply/battery/voltage_now'))/1000000,3);    // напряжение на батарее [В]
    $bt_i=round(intval(file_get_contents('/sys/class/power_supply/battery/current_now'))/1000,1);     // ток при работе от батареи [мА]

    // Контроль параметров сетевого источиника питания (СИП)
    $ac_p=mb_substr(file_get_contents('/sys/class/power_supply/ac/present'), 0, -1);        // наличие СИП [1/0]
    $ac_o=mb_substr(file_get_contents('/sys/class/power_supply/ac/online'), 0, -1);        // питание от СИП или нет [1/0]
    $ac_v=round(intval(file_get_contents('/sys/class/power_supply/ac/voltage_now'))/1000000,3);    // напряжение СИП [В]
    $ac_i=round(intval(file_get_contents('/sys/class/power_supply/ac/current_now'))/1000,1);        // ток при работе от СИП [мА]

    $cpu_t=round(intval(file_get_contents('/sys/devices/platform/sunxi-i2c.0/i2c-0/0-0034/temp1_input'))/1000,1);        // температура чипа [С]

    // Запускаем метод и передаем ему все собранные параметры для дальнейшей обработки
    runScript('Cubietruck metrics',array("ac_v"=>$ac_v,"ac_i"=>$ac_i,"ac_p"=>$ac_p,"ac_o"=>$ac_o,
    	"bt_v"=>$bt_v,"bt_i"=>$bt_i,"bt_p"=>$bt_p,"bt_o"=>$bt_o,"bt_s"=>$bt_s,"bt_c"=>$bt_c,
    	"cpu_t"=>$cpu_t));
  }

  if (file_exists('./reboot')) {
    $db->Disconnect();
    exit;
  }
  sleep(1);
}

DebMes("Unexpected close of cycle: " . basename(__FILE__));
