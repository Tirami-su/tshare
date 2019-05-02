<?php
/************** 查看上架的商品 ****************/
include_once("../../lib/Db.php");
include_once("../../entity/sale.php");
include_once("../../entity/user.php");

$page = $_GET['page'];
$db = new Db();
$user = $_SESSION['user'];

$sales = $db->selects("sale", ['email' => $user->getEmail()]);

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