<?php
require_once "lib/Db.php";
require_once "lib/Mailer.php";

/**
 * 注册，发送邮箱验证码
 * @param String $id 学号
 */
function sendVerification($id) {
	$email = $id."@stu.hit.edu.cn";		// 验证邮箱
	$rand = mt_rand(100000, 999999);	// 验证码
	$body = "验证码：" . $rand;
	$subject = "来自tshare的邮箱验证";

	// 先发送邮件再存数据库
	$mail = Mailer::instance();
	$mail->send($email, $subject, $body);

	$time = time() + 60;	// 验证码有效时间
	// 写入数据库
	$db = new Db();
	$sql = "insert into veri(id, verification, time) values({$id}, '{$rand}', {$time})";
	$db->query($sql);
}

/**
 * 注册验证，确认验证码，并完成对用户的注册
 * @param String $id 学号
 * @param String $password 密码
 * @param String $verification 验证码
 * @return int 验证码错误返回0，验证码失效返回1，用户已存在返回2，注册成功返回3
 */
function register($id, $password, $verification) {
	$flag = 0;		// 错误码

	// 查询验证码是否存在
	$sql = "select id, time from veri where verification='{$verification}'";
	$db = new Db();
	$res = $db->query($sql);

	if($res->num_rows === 0) {
		// 错误验证码(验证码不存在)
		$flag = 0;
	} else {
		$row = $res->fetch_array(MYSQLI_ASSOC);

		// 确认是否是该用户发送的验证码
		if($row['id'] == $id) {

			// 确认验证码是否有效
			if($row['time'] > time()) {
				$sql = "select * from user where id={$id}";
				$res = $db->query($sql);

				// 检查用户是否注册
				if($res->num_rows === 0) {
					//注册用户，将用户信息加入数据库
					$sql = "insert into user(id, password) values({$id}, '{$password}')";
					$db->query($sql);
					$flag = 3;
				} else {
					// 用户已注册过
					$flag = 2;
				}
			} else {
				// 验证码失效
				$flag = 1;
			}

			// 无论验证码是否失效都应该从数据库删除
			$sql = "delete from veri where verification = '{$verification}'";
			$db->query($sql);
		} else {
			// 错误验证码(验证码存在，但是不是该学号的用户申请的)
			$flag = 0;
		}
	}

	return flag;
}

/**
 * 登录验证
 * @param String $id 用户学号
 * @param String $password 密码
 * @return int 用户不存在返回0，密码错误返回1，登录成功返回2
 */
function login($id, $password) {
	$flag = 0;		// 错误码

	// 从数据库中进行查询
	$sql = "select password from user where id={$id}";
	$db = new Db();
	$res = $db->query($sql);

	if($res->num_rows === 0) {
		// 不存在此用户
		$flag = 0;
	} else {
		$row = $res->fetch_array(MYSQLI_ASSOC);
		if($row['password'] === $password) {
			// 登录成功
			$flag =  2;
		} else {
			// 密码错误
			$flag = 1;
		}
	}
	
	return $flag;
}

?>