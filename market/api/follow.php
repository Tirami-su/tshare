<?php
/************* 关注商品 ***************/
include_once("../../lib/Db.php");
include_once("../../entity/attention.php");
include_once("../../entity/user.php");
session_start();

$id = $_GET['id'];
$db = new Db();
$user = $_SESSION['user'];

$attention = new attention();
$attention->setId($id);
$attention->setEmail($user->getEmail());

$db->insert("attention", $attention);
echo json_encode(['code' => 1, 'msg' => '关注成功']);
?>