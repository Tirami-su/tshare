<?php
/************** 列出关注的商品 *****************/
include_once("../../lib/Db.php");
include_once("../../entity/user.php");
include_once("../../entity/attention.php");
include_once("../../entity/sale.php");
session_start();

$page = $_GET['page'];

$user = $_SESSION['user'];
$db = new Db();

$list = $db->selects("attention", ['email' => $user->getEmail()]);
$sales = array();
for($i=0;$i<count($list);$i++) {
	$sales[$i] = $db->select("sale", ['id' => $list[$i]->getId()]);
}

// 分页
$json_data = array();
$index = 0;
for($i=0;$i<count($sales);$i++) {
	$sale = array();
	$sale['id'] = $sales[$i]->getId();
	$sale['title'] = $sales[$i]->getTitle();
	$sale['price'] = $sales[$i]->getPrice();
	$sale['view'] = $sales[$i]->getView();
	$picture = $sales[$i]->getPicture();
	$list = explode(';', $picture);
	$sale['pic'] = $list;

	$json_data[$index][] = $sale;
	if(count($json_data[$index]) == 10) {
		$index++;
	}
}

if($page < count($json_data)) {
	echo json_encode(['code' => 1, 'msg' => '查看成功', 'amount' => count($sales), 'data' => $json_data[$page-1]]);
} else {
	echo json_encode(['code' => 0, 'msg' => '查看失败']);
}

?>