<?php
/**************** 登录抢占 ******************/
include_once(dirname(__FILE__)."/../entity/user.php");
include_once(dirname(__FILE__)."/Db.php");
include_once(dirname(__FILE__)."/cookie.php");

/**
 * 根据session_id查询是否有人抢占登录。如果用户正常退出后再登录并不算抢占
 * @return bool 如果有则返回true并设置抢占标志位，没有返回false并撤销抢占标志位
 */
function isSeize() {
	if(!isset($_SESSION)) {
		session_start();
	}

	$db = new Db();
	$user = $_SESSION['user'];
	$user = $db->select('user', ['email'=>$user->getEmail()]);

	if($user->getSession_id() == '0') {
		// 上一次用户正常退出登录了
		$_SESSION['seize'] = false;
		$user->setSession_id($_SESSION['id']);
		$db->update("user", $user);
	} else {
		if($user->getSession_id() != $_SESSION['id']) {
			// 设置抢占标志位
			$_SESSION['seize'] = true;

			// 一旦被登录抢占就删除cookie
			cookie::destroy("email");
			cookie::destroy("pwd");
		} else {
			$_SESSION['seize'] = false;
		}

		return $_SESSION['seize'];
	}
}
?>