<?php
require_once "../../helper.php";

$id = $_GET['id'];
sendVerification($id);	// 发送邮箱验证码
?>