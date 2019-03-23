<?php
/************ 获取登录cookie参数 **************/
include_once("../lib/cookie.php");
include_once("../lib/Db.php");
include_once("../entity/user.php");

$flag = [];

$id = cookie::get("id");
if($id === NULL) {
	// 不存在该cookie
	$flag = ['code' => 0, 'data' => 'null'];
} else {
	$db = new Db();
	$user = $db->select("user", ['id' => $id]);
	$pwd = cookie::get('pwd', $user->getCookie_key());
	$flag = ['code' => 1, 'data' => ['id' => $id, 'pwd' => $pwd]];
}

echo json_encode($flag);

?>