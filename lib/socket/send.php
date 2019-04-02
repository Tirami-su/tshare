<?php
include_once(dirname(__FILE__) . "/../../entity/notice.php")
/**
 * 发送消息给指定的客户端套接字
 * @param $from 发件人(可以为空，表示由系统管理员发送消息)
 * @param $to 收件人(可以为空，表示向所有用户发送消息)
 * @param $content 消息内容
 */
function send($from, $to, $content) {
	// 处理发件人为空的情况
	if($from == '') {
		$from = NULL;
	}

	if($to == '') {
		$to = NULL;
	}

	$url = "http://www.haoye.com:3121";
	$data = [
		'type' => 'publish',
		'data' => ['from' => $from, 'to' => $to, 'content' => $content]
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