<?php
/*退出登录，修改登录状态*/

include_once("../lib/Db.php");
include_once("../entity/user.php");

session_start();

$db = new Db();
$user = $_SESSION['user'];		// 获取当前登录的对象
// 修改退出时间
$user->setLogout_time(time());
// 撤销session_id
$user->setSession_id(0);

$db->update("user", $user);		// 更新数据库

?>