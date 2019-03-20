<?php
session_start();

if(array_key_exists("user", $_SESSION)) {
	// 用户已经登录
} else {
	// 用户没有登录
	header("Location:../");
}

?>