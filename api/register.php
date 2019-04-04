<?php
/******************** 注册 ************************/

include_once("../lib/Db.php");
include_once("../entity/user.php");

$id = $_POST['id'];
$username = $_POST['name'];
$password = $_POST['pwd'];

$flag = register($id, $username, $password);
echo json_encode($flag);


/**
 * 完成对用户的注册
 * @param String id     学号
 * @param String $username 昵称
 * @param String $password 密码
 */
function register($email, $username, $password) {
	$db = new Db();
	$user = new user();

	$res = $db->select("user", ['email' => $email]);
	if($res !== NULL) {
		return ['code' => 0, 'msg' => '邮箱已注册'];
	}

	$user->setEmail($email);
	$user->setUsername($username);
	$user->setPassword($password);

	$db->insert("user", $user);
	return ['code' => 1, 'msg' => '注册成功'];
}

?>