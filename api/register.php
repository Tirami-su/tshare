<?php
/******************** 注册 ************************/

include_once("../lib/Db.php");
include_once("../entity/user.php");

$id = $_POST['id'];
$username = $_POST['name'];
$password = $_POST['pwd'];

register($id, $username, $password);
echo ['code' => 1, 'msg' => '注册成功'];


/**
 * 完成对用户的注册
 * @param String id        学号
 * @param String $username 昵称
 * @param String $password 密码
 */
function register($id, $username, $password) {
	$db = new Db();
	$user = new user();
	$user->setId($id);
	$user->setUsername($username);
	$user->setPassword($password);

	$db->insert("user", $user);
}

?>