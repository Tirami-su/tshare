<?php
/**
 * 注册验证
 * 1. 确认验证码及其有效性
 * 2. 确认验证码与学号的对应关系
 * 3. 确认学号没有被注册过
 */

include_once("../lib/Db.php");

/**
 * 注册验证，确认验证码，并完成对用户的注册
 * @param String $id 学号
 * @param String $password 密码
 * @param String $verification 验证码
 * @return int 验证码错误返回0，验证码失效返回1，用户已存在返回2，注册成功返回3
 */
function registerCheck($id, $password, $verification) {
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

	return $flag;
}

$id = $_GET['id'];		// 获取学号
$pwd = $_GET['pwd'];	// 获取密码
$veri = $_GET['veri'];	// 获取邮箱验证码

$flag = registerCheck($id, $pwd, $veri);
echo $flag;

?>