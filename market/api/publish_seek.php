<?php
/************ 收购商品 ***************/
include_once("../../lib/Db.php");
include_once("../../entity/user.php");
include_once("../../entity/seek.php");
session_start();

$name = $_GET['name'];
$description = $_GET['description'];

$db = new Db();
$user = $_SESSION['user'];
$seek = new seek();

$seek->setEmail($user->getEmail());
$seek->setName($name);
$seek->setDescription($description);
$seek->setTime(time());
$db->insert("seek", $seek);
echo json_encode(['code' => 1, 'msg' => '发布成功']);

?>