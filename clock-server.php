<?php
// MIMEタイプを出力
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
// TIME ZONEの設定
date_default_timezone_set('Asia/Tokyo');

// 現在時刻を表示
for (;;){
	$time = date('r');
	echo "data: The server time is: {$time}\n\n";
	echo "data: The server another time is: {$time}\n\n";
	ob_flush(); flush();
	sleep(1);
}
?>