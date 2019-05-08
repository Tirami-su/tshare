<?php
/***************** 发送消息给指定的客户端套接字 ******************/
include_once(dirname(__FILE__) . "/../../entity/notice.php");
include_once(dirname(__FILE__) . "/../request.php");
$config = require(dirname(__FILE__) . "/../../config.php");
$domain = $config['domain'];

function send($from, $to, $content) {
	global $domain;
	// 处理发件人为空的情况
	if($from == '') {
		$from = NULL;
	}

	// 处理收件人为空的情况
	if($to == '') {
		$to = NULL;
	}

	$notice = ['sender' => $from, 'address' => $to, 'content' => $content, 'time' => time()];

	$url = "http://".$domain.":3121";
	$data = [
		'type' => 'notice',
		'data' => json_encode($notice)
	];

	$return = request::sendPostRequest($url, $data, 5);

	if($return == 'success') {
		echo json_encode(['code' => 1, 'msg' => '发送成功']);
	} else if($return == 'offline') {
		echo json_encode(['code' => 1, 'msg' => '用户离线']);
	} else {
		echo json_encode(['code' => 0, 'msg' => '未知错误']);
	}
}
?>