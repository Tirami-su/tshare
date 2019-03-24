<?php

require_once dirname(__FILE__).'/PHPMailer/PHPMailer.php';
require_once dirname(__FILE__).'/PHPMailer/SMTP.php';

class Mailer {
	// 发送邮件的对象
	private $mail;

	public function __construct() {
		$this->init();
	}

	/**
     * 发送邮件，以官方邮箱账号向目标邮箱发送验证码
     * @param String $to 目标邮箱
     * @param String $subject 邮件主题
     * @param String $body 邮件内容
     * @return  发送成功返回true，失败返回错误码
     */
	public function send($to, $subject, $body) {
		// 设置邮件接收人，邮件主题和内容
		$this->mail->addAddress($to);
		$this->mail->Subject = $subject;
		$this->mail->Body = $body;

		try {
			$this->mail->send();
			return true;
		} catch (Exception $e) {
			return $this->mail->errorInfo;
		}
	}

	/**
	 * 对mail对象进行一些初始化
	 */
	private function init() {
		$this->mail = new PHPMailer();
		
		/* 并对对象进行一些默认的设置 */		
    	$this->mail->isSMTP(true);					// 使用SMTP发送
    	$this->mail->Host = 'smtp.163.com';		// 使用163邮件服务器发送
    	$this->mail->SMTPAuth = true;			// 使用身份验证
    	$this->mail->Username = 'tshare_service@163.com';		// 官方邮箱账号
    	$this->mail->Password = 'tshare123';	// 官方邮箱授权码
    	$this->mail->SMTPSecure = 'ssl';		// 使用ssl加密协议
    	$this->mail->Port = 994;				// 使用994端口
    	// ssl认证
    	$this->mail->SMTPOptions = array(
	        'ssl' => array(
	        'verify_peer' => false,
	        'verify_peer_name' => false,
	        'allow_self_signed' => true
	        )
	    );
    	$this->mail->CharSet = 'UTF-8';			// 设置编码
    	$this->mail->setFrom('tshare_service@163.com', 'Tshare客服团队');		// 设置邮件的发送者		
		$this->mail->isHTML(true);				// 表示用HTML形式发送邮件
	}

	/**
	 * 获取邮件发送对象
	 */
	public function getMailer() {
		return $this->mail;
	}
}

?>