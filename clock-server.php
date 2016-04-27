<?php
// MIMEタイプを出力
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

/** 今までコードはオリジナルとして残しておく
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
*/

// 二箇所のタイムゾーンから時刻を取り出し1秒毎にクライアントへ転送
for(;;){
	//東京時間の取得
	date_default_timezone_set('Asia/Tokyo');	
	$_time_Japan = date('r');
	//ドイツ時間の取得
	date_default_timezone_set('Europe/London');
	$_time_England = date('r');	
	//クライアントに両時刻を送出
	sendMessage($_time_Japan, $_time_England);
	//１秒間スリープ
	sleep(1);
}

// 日本時刻とドイツ時刻を送出する関数:書式"time1@time2"
function sendMessage($_time1, $_time2){
	echo "data: $_time1@$_time2\n\n";
	ob_flush();
	flush();
}
?>