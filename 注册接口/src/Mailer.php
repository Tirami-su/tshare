<?php

// 注意声明命名空间

// 需要引进phpmailer.php
include_once("../../lib/phpmailer.php");

/**
 * 封装邮件发送类，采用单例设计模式
 */
class Mailer {
	// 本类唯一一个实例化对象
	protected static $instance;

	// 发送邮件的对象
	private $mail;

	protected function __construct() {
		// 构造方法被保护，无法在外部实例化对象
		$this->init();
	}

	/**
	 * 获取该类的实例化对象
	 */
	public static function instance() {
		if(is_null(self::$instance)) {
			self::$instance = new static();
		}
		return self::$instance;
	}

	/**
     * 发送邮件，以官方邮箱账号向目标邮箱发送验证码
     * @param String $to 目标邮箱
     * @param String $subject 邮件主题
     * @param String $body 邮件内容
     * @return bool 发送成功返回true，失败返回false
     */
	public function send($to, $subject, $body) {
		// 设置邮件接收人和邮件内容
		$this->mail->addAddress($to, '');
		$this->mail->Subject = $subject;
		$this->mail->Body = $body;

		if(!$this->mail->send()) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * 对mail对象进行一些初始化
	 */
	private function init() {
		$this->mail = new PHPMailer();
		
		/* 并对对象进行一些默认的设置 */

		// 使用SMTP发送
    	$this->mail->isSMTP();
    	// 使用163邮件服务器发送
    	$this->mail->Host = 'smtp.163.com';
    	// 使用身份验证
    	$this->mail->SMTPAuth = true;
    	// 官方邮箱
    	$this->mail->Username = '';
    	// 官方邮箱密码
    	$this->mail->Password = '';
    	// 设置编码
    	$this->mail->CharSet = 'UTF-8';
    	// 设置邮件的发送者
    	$this->mail->setFrom('', '163');
    	// 设置邮件的回复者
		$this->mail->addReplyTo('', '163');
		// 表示用HTML形式发送邮件
		$this->mail->isHTML(true);
	}
}

?>