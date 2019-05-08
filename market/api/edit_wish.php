<?php
/*************** 编辑心愿单 *****************/
include_once("../../entity/wish.php");
include_once("../../lib/Db.php");

$id = $_GET['id'];
$name = isset($_GET['name']) ? $_GET['name'] : NULL;
$delete = isset($_GET['delete']) ? $_GET['delete'] : NULL;

$db = new Db();
$wish = $db->select("wish", ['id' => $id]);

if($delete !== NULL) {
	$wish->setDelet(1);
	$db->update("wish", $wish);
	echo json_encode(['code' => 1, 'msg' => '修改成功']);
	exit;
}

if($name !== NULL) {
	$wish->setName($name);
	$wish->setTime(time());
	$db->update("wish", $wish);
	echo json_encode(['code' => 1, 'msg' => '修改成功']);
	exit;
}
?>