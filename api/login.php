<?php
/**
 * 登录验证
 * 1. 确认用户名(学号)是否存在
 * 2. 确认密码是否正确
 */

include_once("../lib/Db.php");

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

$id = $_GET['id'];		// 获取学号
$pwd = $_GET['pwd'];	// 获取密码

$flag = login($id, $pwd);
echo $flag;

?>