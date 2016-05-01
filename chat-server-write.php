<?php
include_once('chat-server-common.inc.php');

$name =	empty($_GET["name"]) ? "名無し" : $_GET["name"];
$body =	empty($_GET["body"]) ? "(空)" : $_GET["body"];

$name = htmlspecialchars($name, ENT_QUOTES);
$body = htmlspecialchars($body, ENT_QUOTES);

$stmt =	$pdo->prepare("INSERT INTO logs (name, body) VALUES (?,?)");
$stmt->execute(array($name, $body));

echo "ok\n";

?>
