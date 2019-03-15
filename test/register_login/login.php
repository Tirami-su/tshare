<?php
require_once("../../helper.php");

$id = $_GET['id'];
$pwd = $_GET['pwd'];

$flag = login($id, $pwd);
switch ($flag) {
	case 0:
		echo "用户不存在";
		break;
	case 1:
		echo "密码错误";
		break;
	case 2:
		echo "登录成功";
		break;
	default:
		echo "未知错误";
		break;
}
?>