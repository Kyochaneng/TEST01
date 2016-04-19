<?php
// MIMEタイプを出力
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
// 現在時刻を表示
for (;;){
	$time = date('r');
	echo "data: The server time is: {$time}\n\n";
	ob_flush(); flush();
	sleep(1);
}
?>