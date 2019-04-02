<?php

$sender = $_POST['from'];
$address = $_POST['to'];
$content = $_POST['content'];
$time = $_POST['time'];

// $sender = $_GET['from'];
// $address = $_GET['to'];
// $content = $_GET['content'];
// $time = $_GET['time'];

$flag = sendMsg($sender, $address, $content, $time);
echo json_encode($flag);

function sendMsg($sender, $address, $content, $time) {
	// 处理发件人和收件人为空的情况
	if($sender == '') {
		$sender = NULL;
	}

	if($address == '') {
		$address = NULL;
	}

	$url = "http://www.haoye.com:3121";
	$data = [
		'type' => 'publish',
		'data' => json_encode(['sender' => $sender, 'address' => $address, 'content' => $content, 'time' => $time])
	];

	$ch = curl_init ();
	curl_setopt ( $ch, CURLOPT_URL, $url );
	curl_setopt ( $ch, CURLOPT_POST, 1 );
	curl_setopt ( $ch, CURLOPT_HEADER, 0 );
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
	curl_setopt ($ch, CURLOPT_HTTPHEADER, array("Expect:"));
	$return = curl_exec ( $ch );
	curl_close ( $ch );

	if($return == 'ok') {
		return ['code' => 1, 'msg' => '发送成功'];
	} else if($return == 'offline') {
		return ['code' => 1, 'msg' => '用户离线'];
	} else if($return == 'fail') {
		return ['code' => 0, 'msg' => '发送失败'];
	} else {
		return ['code' => 0, 'msg' => '未知错误'];
	}
}
?>