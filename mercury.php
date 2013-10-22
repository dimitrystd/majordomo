<?php
exec('mode COM3: BAUD=9600 PARITY=n DATA=8 STOP=1 to=on xon=off odsr=off octs=off dtr=on rts=on idsr=off');
// Несмотря на настройки, порт правильно не откравается, перед запуском скрипта надо запустить родной софт
// причины такого поведения пока не выяснены

$f = fopen("COM3","r+");
try{
	$i=0;
	$cmd = array(0x00,0x0D,0x29,0x0F,0x63, 0xb2, 0xbd );			// Команда "Мгновенные значения"
	$c="";
	for($i=0; $i < count($cmd); $i++){$c .= chr($cmd[$i]);}		// Сформировать строку символов для посылки в COM-порт
	fwrite($f,$c);
	$result=fread($f,14);

	// Результат получаем в шестнадцатиричном виде, но его написание соответствует десятичному значению.
	// Например 0x22 0x87 = 228,7 вольт. Переводим результат в человеческий вид.
	$Uv = ((ord($result[5])>>4)*100)+((ord($result[5])&0x0f)*10)+(ord($result[6])>>4)+((ord($result[6])&0x0f)/10);
	$Ia = ((ord($result[7])>>4)*10)+(ord($result[7])&0x0f)+((ord($result[8])>>4)/10)+((ord($result[8])&0x0f)/100);
	$Pv = ((ord($result[9])&0x0f)*10)+(ord($result[10])>>4)+((ord($result[10])&0x0f)/10)+((ord($result[11])>>4)/100)+((ord($result[11])&0x0f)/1000);

	// Значения Uv, Ia, Pv можно писать в базу...
	echo "<br>Напряжение сети:  ".$Uv ." Uv";
	echo "<br>Сила така :  ".$Ia ." Ia";
	echo "<br>Потребляемая мощьность : ".$Pv." P kVt";
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
fclose($f);

?>
