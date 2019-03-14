<?php

include_once("../../lib/database.php");

$id = $_POST['id'];
$pwd = $_POST['pwd'];
$veri = $_POST['veri'];

$db = Db::instance();
$sql = "select time from veri where verification = '{$veri}'";
$res = $db->query($sql);
if($res === NULL) {
	// 验证码错误
	exit;
}

$row = $res->fetch_array(MYSQLI_ASSOC);
if($row['time'] > time()) {
	// 有效验证码
	$sql = "delete from veri where verification = '{$veri}'";
	$db->query($db);

	// 写入数据库
    $sql = "insert into user(id, password) values({$id}, '{$pwd}')";
	$db->query($sql);
} else {
	// 失效验证码
	exit;
}
?>