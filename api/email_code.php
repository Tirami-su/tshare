<?php
/**************发送邮箱验证码***************/

include_once("../lib/Db.php");
include_once("../lib/Mailer.php");
include_once("../entity/verification.php");

$id = $_GET['id'];	// 获取学号
$flag = sendVerification($id);	// 调用函数发送验证码
echo json_encode($flag);		// 将邮件发送成功或失败的信息以json字符串传递给js

/**
 * 注册，发送邮箱验证码
 * @param String $id 学号
 * @return array[code, msg]
 */
function sendVerification($id) {
	$flag = [];

	$db = new Db();

	$veri = new verification();
	$veri->setId($id);
	$db->delete("verification", $veri);		// 删除之前申请的验证码

	$email = $id."@stu.hit.edu.cn";		// 目标邮箱
	$code = mt_rand(100000, 999999);	// 验证码
	$body = "您好！此电子邮件地址正用于注册Tshare平台帐号，验证码：<h2>".$code."</h2>请勿泄露给他人，10分钟内有效。<br>".
			"如果不是您本人操作，请忽略此邮件，不会有任何情况发生。<br><br>此致<br>Tshare客服团队";
	$subject = 'Tshare注册验证码';

	// 先发送邮件再存数据库
	$mail = new Mailer();
	$info = $mail->send($email, $subject, $body);

	if($info === true) {
		$time = time() + 600;	// 设置验证码有效时间为10min
		
		// 写入数据库
		$veri->setCode($code);
		$veri->setTime($time);
		$db->insert("verification", $veri);
		$flag = ['code' => 1, 'msg' => '邮件发送成功'];
	} else {
		$flag = ['code' => 0, 'msg' => '邮件发送失败'.$info];
	}
	return $flag;
}
?>