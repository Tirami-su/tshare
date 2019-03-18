<?php
/**
* 发送邮箱验证码
* 
*/
use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/PHPMailer.php';
// require 'PHPMailer/Exception.php';
require 'PHPMailer/SMTP.php';

//生成6位数字验证码
$code = mt_rand(100000, 999999);

$mail = new PHPMailer(true);// Passing `true` enables exceptions
try {
    //Server settings
    // $mail->SMTPDebug = 2;// Enable verbose debug output
    $mail->isSMTP();// Set mailer to use SMTP
    $mail->Host = 'smtp.163.com';// Specify main and backup SMTP servers
    $mail->SMTPAuth = true;// Enable SMTP authentication
    $mail->Username = 'tshare_service@163.com';// SMTP username
    $mail->Password = 'tshare123';// SMTP password
    $mail->SMTPSecure = 'ssl';// Enable TLS encryption, `ssl` also accepted
    $mail->Port = 994;// TCP port to connect to

    //Recipients
    $mail->setFrom('tshare_service@163.com', 'Tshare客服团队');
    $mail->addAddress($_GET['id'].'@stu.hit.edu.cn');

    //Content
    $mail->isHTML(true);// Set email format to HTML
    $mail->Subject = 'Tshare注册验证码';
    $mail->Body = "您好！此电子邮件地址正用于注册Tshare平台帐号，验证码：<h2>".$code."</h2>10分钟内有效，请勿泄露给他人。<br>如果不是您本人操作，请忽略此邮件，不会有任何情况发生。<br><br>此致<br>Tshare客服团队";
    $mail->AltBody = "您好！此电子邮件地址正用于注册Tshare平台帐号，验证码：".$code."，10分钟内有效，请勿泄露给他人。\n如果不是您本人操作，请忽略此邮件，不会有任何情况发生。\n\n此致\nTshare客服团队";

    $mail->send();
    echo json_encode([
        'code'=>1, 
        'msg'=>'Message has been sent'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'code'=>0, 
        'msg'=>'Message could not be sent. Mailer Error: '.$mail->ErrorInfo
    ]);
}
?>
