<?php
/*************** 查看心愿墙 ******************/
include_once("../../lib/Db.php");
include_once("../../entity/wish.php");

$page = $_GET['page'];
$low = ($page-1)*10;

$db = new Db();
$count = $db->count("wish");
if($low >= $count) {
	echo json_encode(['code' => 0, 'msg' => '查看失败']);
	exit;
}

$json_data = array();
$index = 0;
while($index < 10) {
	if($count-$low == 0) break;			// 当数据库中能够显示的记录总共都不足10项时

	$wish = $db->select("wish", ['id' => ($count-$low++)]);
	if($wish->getIs_get() == "1" || $wish->getDelete() == "1") {
		// 如果已经完成交易或已经删除，则不需要显示
		continue;
	}

	$data = array();
	$data['name'] = $wish->getName();
	$json_data[$index++] = $data;
}

if(count($json_data) === 0) {
	echo json_encode(['code' => 0, 'msg' => '无心愿单']);
} else {
	echo json_encode(['code' => 1, 'msg' => '查看成功', 'amount' => $count, 'data'=>$json_data]);
}
?>