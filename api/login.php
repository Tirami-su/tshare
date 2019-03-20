<?php
/**
 * 登录验证
 * 1. 确认用户名(学号)是否存在
 * 2. 确认密码是否正确
 */

/*登录之后需要一个session对象来保存当前登录的对象*/
session_start();

include_once("../lib/Db.php");
include_once("../entity/user.php");

$id = $_POST['id'];		// 获取学号
$pwd = $_POST['pwd'];	// 获取密码

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
			// 密码正确，查询用户登录状态
			if($user->getStatus() === '0') {
				// 登录成功，修改用户的登录状态
				$user->setStatus(1);
				$db->update("user", $user);

				// 使用session保存登录用户的完整信息
				$_SESSION['user'] = $user;

				$flag = ['code' => 1, 'msg' => '登录成功'];
			} else {
				// 用户已登录
				$flag = ['code' => 0, 'msg' => '用户已经登录'];
			}
		} else {
			// 密码错误
			$flag = ['code' => 0, 'msg' => '密码错误'];
		}
	}
	return $flag;
}

?>