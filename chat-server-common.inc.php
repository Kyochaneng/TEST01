<?php 
$roomId = isset($_GET["roomId"]) ? $_GET["roomId"] : "test";
if (!preg_match('/[a-z0-9]+/', $roomId)) {
	$roomId = "test";
}


/* roomID をデータベースの名称とするデータベースファイルを作成し
 * そのオブジェクトの実行インスタンスを$pdoとする
 * ：__FILE__: これは今実行しているファイルのディレクトリを見つけるメソッド？かな。
 */
$dbpath = dirname (__FILE__)."/chat-{$roomId}.db";
$pdo = new PDO("sqlite:$dbpath");

/* データベースにテーブルを定義
 * logId : primary key として定義しこのテーブルにデータ（すなわち、name　と body）
 * が挿入されるたびに整数値としてインクリメントされていく。
 * データベースの名称はroomIDの名称をそのままデータベースファイルの名前としている
 * そのデータベースの中に定義するのは logs と言う名前のテーブルで
 * 一度作成したら当然ですが作る必要はない。
 * ：テーブル名：logs
 * : logId
 * : name
 * : body
 */
$pdo->exec("
	CREATE TABLE IF NOT EXISTS logs(
		logId INTEGER PRIMARY KEY,
		name TEXT,
		body TEXT)");
?>		
		