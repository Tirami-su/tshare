<?php
/**
 * 登录验证
 * 1. 确认用户名(学号)是否存在
 * 2. 确认密码是否正确
 * 在浏览器中新建cookie或刷新cookie
 */

/*登录之后需要一个session对象来保存当前登录的对象*/
session_start();

include_once("../lib/Db.php");
include_once("../entity/user.php");
include_once("../lib/cookie.php");

// $id = $_POST['id'];		// 获取学号
// $pwd = $_POST['pwd'];	// 获取密码
$id = "160400423";
$pwd = "520999";

// 是否一周内自动登录
// $auto_login = $_POST['auto_login'];

$flag = login($id, $pwd);
echo json_encode($flag);		// 返回错误信息

/**
 * 登录验证
 * @param String $id 用户学号
 * @param String $password 密码
 * @return array[code, msg] 登录成功code为1，登录失败code为0
 */
function login($id, $password) {
	$flag = [];		// 错误信息

	// 从数据库中进行查询
	$db = new Db();
	$user = $db->select("user", ["id" => $id]);

	if($user === NULL) {
		// 用户不存在
		$flag = ['code' => 0, 'msg' => '用户不存在'];
	} else {
		if($user->getPassword() == $password) {
			// 修改登录时间
			$user->setLogin_time(time());
			// 设置学号和密码的cookie，有效时间一个月
			cookie::set('id', $user->getId(), time()+3600*24*30, false, '', "login_cookie.php");
			$key = cookie::set('pwd', $user->getPassword(), time()+3600*24*30, true, '', "login_cookie.php");
			// 设置自动登陆
			// $auto_login = ($auto_login==1) ? time() : 0;
			// cookie::set('auto_login', $auto_login, time()+3600*24*30, flase, '', 'login_cookie.php');
			// 修改解密密钥
			$user->setCookie_key($key['target_key']);
			// 并随即生成一个session_id
			$_SESSION['id'] = mt_rand(1, 100000);
			// 修改session_id
			$user->setSession_id($_SESSION['id']);
			$db->update("user", $user);

			// 使用session保存登录用户的完整信息
			$_SESSION['user'] = $user;

			$flag = ['code' => 1, 'msg' => '登录成功'];
		} else {
			// 密码错误
			$flag = ['code' => 0, 'msg' => '密码错误'];
		}
	}
	return $flag;
}

?>