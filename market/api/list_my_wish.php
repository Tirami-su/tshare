<?php
/************** 查看心愿单 ****************/
include_once("../../lib/Db.php");
include_once("../../entity/wish.php");
include_once("../../entity/user.php");

$page = $_GET['page'];
$db = new Db();
$user = $_SESSION['user'];

$wishes = $db->selects("wish", ['email' => $user->getEmail()]);

// 分页
$json_data = array();
$index = 0;
for($i=0;$i<count($wishes);$i++) {
	$wish = array();
	$wish['id'] = $wishes[$i]->getId();
	$wish['name'] = $wishes[$i]->getName();

	$json_data[$index][] = $wish;
	if(count($json_data[$index]) == 10) {
		$index++;
	}
}

if($page < count($json_data)) {
	echo json_encode(['code' => 1, 'msg' => '查看成功', 'amount' => count($wishes), 'data' => $json_data[$page-1]]);
} else {
	echo json_encode(['code' => 0, 'msg' => '查看失败']);
}

?>