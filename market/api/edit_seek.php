<?php
/************* 编辑收购 **************/
include_once("../../lib/Db.php");
include_once("../../entity/seek.php");

$id = $_GET['id'];
$data = isset($_GET['data']) ? $_GET['data'] : NULL;
$state = isset($_GET['state']) ? $_GET['state'] ： NULL;

$db = new Db();
$seek = $db->select("seek", ['id' => $id]);

if($state !== NULL) {
	if($state == "0") {
		// 已经收购到了
		$seek->setIs_buy(1);
	} else if($state == "1") {
		// 删除
		$seek->setDelete(1);
	}
	$db->update("seek", $seek);
	echo json_encode(['code' => 1, 'msg' => '修改成功']);
	exit;
}

if($data !== NULL) {
	$data = json_decode($data);
	foreach ($data as $key => $value) {
		$func = "set".ucfirst($key);
		$seek->$func($value);
	}
	$seek->setTime(time());
	$db->update("seek", $seek);
	echo json_encode(['code' => 1, 'msg' => '修改成功']);
}
?>