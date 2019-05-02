<?php
/************* 添加心愿单 *************/
include_once("../../entity/wish.php");
include_once("../../lib/Db.php");
include_once("../../entity/user.php");
session_start();

$name = $_GET['name'];

$db = new Db();
$user = $_SESSION['user'];
$wish = new wish();
$wish->setEmail($user->getEmail());
$wish->setName($name);
$wish->setTime(time());

$db->insert("wish", $wish);
echo json_encode(['code' => 1, 'msg' => '添加成功']);
?>