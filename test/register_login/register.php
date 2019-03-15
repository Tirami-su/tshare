<?php
require_once("../../helper.php");

$id = $_GET['id'];
$password = $_GET['pwd'];
$verification = $_GET['veri'];

// 进行注册
$flag = register($id, $password, $verification);

switch ($flag) {
	case 0:
		echo "验证码错误";
		break;
	case 1:
		echo "验证码失效";
		break;
	case 2:
		echo "用户已注册";
		break;
	case 3:
		echo "注册成功";
		break;
	default:
		echo "未知错误";
		break;
}

?>