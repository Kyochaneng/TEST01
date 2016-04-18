<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
for (;;){
	$time = date('r');
	echo "data: The server time is: {$time}\n\n";
	ob_flush(); flush();
	sleep(1);
}
?>