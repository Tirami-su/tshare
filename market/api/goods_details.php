<?php
/************ 商品详情 ***************/
include_once("../../lib/Db.php");
include_once("../../entity/sale.php");

$id = $_GET['id'];

$db = new Db();
$sale = $db->select("sale", ['id' => $id]);

if($sale === NULL) {
	echo json_encode(['code' => 0, 'msg' => '查看失败']);
} else {
	$data = array();
	$data['title'] = $sale->getTitle();
	$data['category'] = $sale->getCategory();
	$data['brand'] = $sale->getBrand();
	$data['description'] = $sale->getDescription();
	$data['buy_price'] = $sale->getBuy_price();
	$data['buy_way'] = $sale->getBuy_way();
	$data['new'] = $sale->getNew();
	$data['price'] = $sale->getPrice();
	$data['bargain'] = $sale->getBargain();
	$data['delivery'] = $sale->getDelivery();
	$data['view'] = $sale->getView();
	$picture = $sale->getPicture();
	$list = explode(";", $picture);
	$data['picture'] = $list;
	echo json_encode(['code' => 1, 'msg' => '查看成功', 'data' => $data]);

	$sale->setView($data['view']+1);
	$db->update("sale", $sale);
}
?>