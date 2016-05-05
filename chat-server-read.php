<?php

include_once('chat-server-common.inc.php');
header('Content-Type: text/event-stream');
header('cache-Control: no-cache');

$lastId = isset($_SERVER["HTTP_LAST_EVENT_ID"]) ? intval($_SERVER["HTTP_LAST_EVENT_ID"]) : 0;
if ($lastId == 0) {
	$lastId = isset($_GET["lastEventId"]) ?	intval($_GET["lastEventId"]) : 0;
}

/* 接続して初めてデータベースからデータ（メッセージ）を取り出すための prepare文。
 * とりあえず５メッセージ文を取り出しoutputLogs関数をコールし
 * 溜まっているメッセージをクライアントに送出する。
 * これ以降は下記のfor文のループの中で送出する。
*/
$stmt = $pdo->prepare("SELECT * FROM logs WHERE logId > ? ORDER BY logId DESC LIMIT 5");
$stmt->execute(array($lastId));
$logs = $stmt->fetchAll();
outputLogs($logs);

/* データベースからデータ（メッセージ）を取り出すための prepare文であり
 * prepare文の中の？の部分に入る変数この場合 lastId とlogIdを比較し、
 * lastId以降（クライアントに送出したメッセージ以降）の次のメッセージを取り出す
 * 実際、このSQL文が実行されるのは、下記のfor文の中でのexecute, fetchAllで処理される。
 */
$stmt = $pdo->prepare("SELECT * FROM logs WHERE logId > ? ORDER BY logId DESC");


/* 一秒毎にクライアントに送るメッセージを$logsに格納する。メッセージがない場合は
 * $logsが空でありその場合は関数outputLogsで何もされずにリターンされてくる
 */
for (;;) {
	$stmt->execute(array($lastId));
	$logs = $stmt->fetchAll();
	outputLogs($logs);
	sleep(1);
}

/* 関数 outputLogs
 * 引数：データベース chat-XX.db に格納されているメッセージ($logs)をクライアントへ送出
 * 解説：引数$logsには、それまでデータベースに保管されたユーザのメッセージが logIDをキーに
 * 　　　新しいものから降順に配列として格納されている。$logsの中身がない場合、すなわち
 * 　　　一定期間にチャットがされない場合もあるのでその場合は何もせずにリターンする。
 * 　　　データが空でないなら、この関数で、配列をリバースして、まず、logid をキーに昇順に並べ替える。
 * 　　　昇順に並んだ数値を foreach文を使って一行ずつ（すなわち、１メッセージづつ）echo文で
 * 　　　クライアント側にデータとして送出する。
 * 　　　logIdがキーポイント。
 * 　　　logIdはクライアントからのメッセージをデータベースに書き込むたびインクリメントされメッセージの
 * 　　　識別に使用される。クライアントに送出した$logidを$lastIdと記録しておき、次に送るメッセージは
 * 　　　このlastIdの次に大きいlogIdのメッセージを送出するようにしている。
 */
function outputLogs($logs) {
	global $lastId;
	
/*  送るメッセージが空の場合、空のデータを送出する（echo ": \n\n")ことで
 *  レスポンスを全く返さないと言う無限ループ向けの対策をする
 *  これはこのアプリをHeroku上で動かすときの
 *  Herokuの持つ制限タイムアウト対策（３０秒何もレスポンスがない場合切断される）
 *  この機能を入れることでクライアント側にも処理が必要かと思ったが特にいらないらしい。
 * 
 */	
	if (!$logs) {
		echo ": \n\n";
		return;
	}
	$logs = array_reverse($logs);
	foreach ($logs as $row) {
		$logId = $row["logId"];
		$log = json_encode(array(
			'logId' => $logId,
			'name' => $row["name"],
			'body' => $row["body"]));
		echo "id: $logId\n";
        echo "data: $log\n\n";
		if ($lastId < $logId) $lastId = $logId;
		ob_flush();
		flush();
	}
}
?>



