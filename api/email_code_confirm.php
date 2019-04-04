<?php
/*验证邮箱的验证码*/

include_once("../lib/Db.php");
include_once("../entity/verification.php");

$id = $_POST['id'];					// 获取学号
$verification = $_POST['email_code'];		// 获取验证码
$flag = id_code_confirm($id, $verification);
echo json_encode($flag);			// 将错误信息以json字符串形式返回

// $id = "160400423";
// $verification = "146561";
// var_dump(id_code_confirm($id, $verification));

/**
 * 验证邮箱验证码的正确性，匹配性和有效性
 * @param String $id 学号
 * @param String $code 验证码
 * @return array[code, msg] 验证成功code为1，验证失败code为0
 */
function id_code_confirm($email, $code) {
	$flag = [];		// 返回信息

	$db = new Db();
	$condition = ['code' => $code];
	$veri = $db->selects("verification", $condition);		// 根据验证码查询数据库

	if($veri === NULL) {
		// 验证码不存在
		$flag = ['code' => 0, 'msg' => '验证码错误'];
	} else {
		$veri = $veri[0];
		if($veri->getEmail() == $email) {
			if($veri->getTime() > time()) {
				$flag = ['code' => 1, 'msg' => '验证成功'];
			} else {
				// 验证码失效
				$flag = ['code' => 0, 'msg' => '验证码失效'];
			}
			// 无论验证码是否失效，都进行删除
			$db->delete("verification", $veri);
		} else {
			$flag = ['code' => 0, 'msg' => '验证码错误'];
		}
	}

	return $flag;
}

?>