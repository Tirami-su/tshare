<?php
/************** 查看收购的商品 ****************/
include_once("../../lib/Db.php");
include_once("../../entity/seek.php");
include_once("../../entity/user.php");

$page = $_GET['page'];
$db = new Db();
$user = $_SESSION['user'];

$seeks = $db->selects("seek", ['email' => $user->getEmail()]);

// 分页
$json_data = array();
$index = 0;
for($i=0;$i<count($seeks);$i++) {
	$seek = array();
	$seek['id'] = $seeks[$i]->getId();
	$seek['name'] = $seeks[$i]->getName();
	$seek['description'] = $seeks[$i]->getDescription();

	$json_data[$index][] = $seek;
	if(count($json_data[$index]) == 10) {
		$index++;
	}
}

if($page < count($json_data)) {
	echo json_encode(['code' => 1, 'msg' => '查看成功', 'amount' => count($seeks), 'data' => $json_data[$page-1]]);
} else {
	echo json_encode(['code' => 0, 'msg' => '查看失败']);
}

?>