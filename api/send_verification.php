<?php
/*用于响应注册时获取邮箱验证码按钮，并通过邮件发送验证码*/

include_once("../lib/Db.php");
include_once("../lib/Mailer.php");

$id = $_GET['id'];	// 获取学号
$flag = sendVerification($id);	// 调用函数发送验证码
echo $flag;

/**
 * 注册，发送邮箱验证码
 * @param String $id 学号
 * @return int 邮件发送成功返回1，发送失败返回0
 */
function sendVerification($id) {
	$db = new Db();
	$sql = "delete from veri where id={$id}";
	$db->query($sql);

	$email = $id."@stu.hit.edu.cn";		// 验证邮箱
	$rand = mt_rand(100000, 999999);	// 验证码
	$body = "验证码：" . $rand;
	$subject = "来自tshare的邮箱验证";

	// 先发送邮件再存数据库
	$mail = new Mailer();
	$flag = $mail->send($email, $subject, $body);

	if($flag === true) {
		$time = time() + 60;	// 验证码有效时间
		// 写入数据库
		$sql = "insert into veri(id, verification, time) values({$id}, '{$rand}', {$time})";
		$db->query($sql);

		return 1;
	}
	return 0;
}
?>