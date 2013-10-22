<?php
include_once("./lib/common.class.php");
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" type="text/css">
</head>
<body>
<h3>Маршрутки в город</h3>
<?php
$cmdUrl = 'http://transit.in.ua/ImportStop.php?Id=46';
$pageContent = getURL($cmdUrl, 0);
$pattern = '/(?:<tr\s?>\s+<td\s.*?>\s+(?<route>124\pL|126)<\/td>\s+<td\s.*?>\s+(?<time1>[\s\w,\pL]+)<\/td>\s+<td\s.*?>\s+(?<time2>[\s\w,\pL]+)<\/td>)/iu';
if (preg_match_all($pattern, $pageContent, $m, PREG_SET_ORDER)) {
?>
	<table class="table table-bordered">
		<tr>
			<td class="table_header">
				Маршрут</td>
			<td class="table_header">
				Ближайший</td>
			<td class="table_header">
				Следующий</td>
		</tr>
<?php
	foreach($m as $schedule){
		echo '<tr><td>'.$schedule['route'].'</td>';
		echo '<td>'.$schedule['time1'].'</td>';
		echo '<td>'.$schedule['time2'].'</td></tr>';
	}
?>
	</table>
<?php
}
?>
</body>
</html>