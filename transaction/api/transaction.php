<?php
/************* 双方准备交易 ***************/

include_once("../../lib/Db.php");
include_once("../../entity/goods.php");
include_once("../../entity/transaction.php");
include_once("../../entity/user.php");
include_once("../../lib/socket/send.php");
session_start();

/**
 * 接收参数：商品编号
 */

// $id = $_GET['id'];
$gid = "1";

$db = new Db();
$user = $_SESSION['user'];
$from = $user->getEmail();

// 查询一下该用户是否已经与该商品存在交易行为
$flag = $db->selects("transaction", ['gid' => $gid, 'email' => $from]);
if($flag !== NULL) {
	// 该用户已经对该商品进行了交易
	echo json_encode(['code' => 0, 'msg' => '消息已经发送，请耐心等待']);
	exit();
}


// 这是该用户第一次交易该商品
$goods = $db->select("goods", ['id' => $gid]);
if($goods !== NULL) {
	// 商品存在
	$to = $goods->getEmail();
	$first = $db->select("user", ['email' => $to]);

	/************** 编写消息内容 ********************/
	$content = "";
	if($goods->getIsSell() == "0") {
		// 收购
		$content = "亲爱的 <b>" . $first->getUsername() . "</b>，你想要的物品 <b>" . $goods->getName() . "</b> 有一位同学 <b>". $user->getUsername() ."</b>有意向出售";
	} else {
		// 出售
		$content = "亲爱的 <b>" . $first->getUsername(). "</b>，您上架的物品 <b>" . $goods->getName() . "</b> 已经被 <b>". $user->getUsername() ."</b>看中了";
	}
	// 发送消息
	send($from, $to, $content);

	// 增加一条交易记录
	$tran = new transaction();
	$tran->set(['gid' => $gid, 'email' => $from, 'time' => time()]);
	$db->insert("transaction", $tran);

	// echo json_encode(['code' => 1, 'msg' => '消息发送成功']);
} else {
	echo json_encode(['code' => 0, 'msg' => '数据库查询出错']);
	// var_dump(['code' => 0, 'msg' => '数据库查询出错']);
}
?>