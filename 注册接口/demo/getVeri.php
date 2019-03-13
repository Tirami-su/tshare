<?php
include_once("../src/Mailer.php");

$id = $_POST['id'];
var_dump($id);

$mail = Mailer::instance();

$to = $id."@stu.hit.edu.cn";
$subject = "tshare邮箱验证";
$rand = mt_rand(100000, 999999);
$body = "验证码：" . $rand;
$mail->send($to, $subject, $body);

$time = time() + 60;
$sql = "insert into veri(verification, time) values('{$rand}', {$time})";
$db = Db::instance();
$db->query($sql);

?>