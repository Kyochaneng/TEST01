<?php

$roomId = isset($_GET["roomId"]) ? $_GET["rommId"] : "test";
$serverRead = "chat-server-read.php?roomId=$roomId";
$serverWrite = "chat-server-write.php?roomId=$roomId";

?>

<!DOCTYPE html>
<html><head><meta charset="UTF-8">

<title>SSEチャット</title>
<link rel="stylesheet" href="./likeLine02.css">


</head>

<body>
<h1>SSEを使ったチャット - <?php echo $roomId ?></h1>

<!-- 発言するときのフォーム -->
<div id="top">名前:<input id ="name" size="13">
本文:<input id="body" size="40">
<button onclick="chatWrite()">発言</button></div>

<!-- メッセージを表示する部分 -->
<div id="disp"></div>
<div id ="wrapper"></div>

<script type="text/javascript">
window.onload = function() {
	var src = new EventSource('<?php echo $serverRead ?>');
//    var src = new EventSource('chat-server-read.php');
	src.onmessage = function (e) {
//		console.log("受け取ったよ:" + e.data);
        console.log("受け取ったよ:");
		$("body").value = "";
		var t = JSON.parse(e.data);

		
		var speaker =
		  ($("name").value == t.name) ? "self" : "friend";
        var nametag = speaker + "_name";

        var body =
          (speaker == "self") ? "right" : "left";
        var body = body + "_balloon";
        
        $("disp").innerHTML =
            "<div id ='wrapper'></div>" +
            "<p class= '" + nametag +"' >" + t.logId + "." + t.name + "</p>" +
        	"<div class= '" + body + "'>" + t.body + "</div></div>" +
        	"<div class='clear_balloon'/>"　+
        	$("disp").innerHTML;
        	
             

		  
//		var block = speaker + "_box";
//		var arrow = "arrow_" + speaker;
//		var nametag = speaker + "_name";
//		console.log(nametag);
		
//		$("disp").innerHTML =
//			"<div class='" + block + "'>" +
//			"<p class= '" + nametag +"' >" + t.logId + "." + t.name + "</p>" +
////          "<p class=self_name>" + t.logId + "." + t.name + "</p>" +
//           "<div id = '" + arrow + "'>" + t.body + "</div></div>" +
//			$("disp").innerHTML;
	}
}

function chatWrite() {
	var url = "<?php echo $serverWrite ?>" + "&name=" + $U('name') + "&body=" + $U('body');
	console.log(url);
	// リクエストを送出する相手をurl で指定しその通信結果を表示させる関数{consoleにログを書き込む}をコールさせる
	ajax(url, function (res) {
		console.log("書き込みました:" + res);
		$("body").value = "";
	});
}

function $U(id) {
	var u = $(id).value;
	return encodeURIComponent(u);
}

function $(id) {
	return document.getElementById(id);
}

function ajax (url, callback) {
	// HTTPリクエストを発行のためXMLHttpRequest のインスタンスを作成
	var xhr = new XMLHttpRequest();
	//Open メソッドで GETリクエストを開始
	xhr.open("GET", url, true);
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && xhr.status == 200) {
		// リクエストは終了し Status が OK の場合、レスポンス結果を変数sに入れて
		var s = xhr.responseText;
		// このfunction ajax をコールした元で指定しているコールバック関数をコールして、すなわちconsoleにログ（"書き込みました"）を書く
		callback(s);
	}
 };
    // 通信結果が失敗に終わった場合 nullをsendする----???
    xhr.send(null);
 }
 </script>
 </body></html>