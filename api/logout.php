<?php
/*退出登录，修改登录状态*/

include_once("../lib/Db.php");
include_once("../entity/user.php");

session_start();

$db = new Db();
$user = $_SESSION['user'];		// 获取当前登录的对象
$user->setStatus(0);			// 修改登录状态
$db->update("user", $user);		// 更新数据库

?>