<?php
/***************** 发布商品 ****************/
include_once("../../lib/Db.php");
include_once("../../entity/sale.php");
include_once("../../entity/user.php");
session_start();
/**
 * 接收参数：
 * 商品标题：title
 * 商品分类：category
 * 商品品牌：brand			非必填
 * 商品描述：description
 * 买入价格：buy_price		非必填
 * 买入渠道：buy_way			非必填
 * 是否全新：new				非必填
 * 预期价格：price
 * 可否议价：bargain			非必填
 * 可否配送：delivery		非必填
 * 商品图片：picture
 */

$arr = array();
$arr['title'] = $_GET['title'];
$arr['category'] = $_GET['category'];
$arr['brand'] = isset($_GET['brand']) ? $_GET['brand'] : NULL;
$arr['description'] = $_GET['description'];
$arr['buy_price'] = isset($_GET['buy_price']) ? $_GET['buy_price'] : NULL;
$arr['buy_way'] = isset($_GET['buy_way']) ? $_GET['buy_way'] : NULL;
$arr['new'] = isset($_GET['new']) ? $_GET['new'] : NULL;
$arr['price'] = $_GET['price'];
$arr['bargain'] = isset($_GET['bargain']) ? $_GET['bargain'] : NULL;
$arr['delivery'] = isset($_GET['delivery']) ? $_GET['delivery'] : NULL;
$picture = $_FILES['picture'];
$picArr = array();
foreach ($picture as $key => $value) {
	$picArr[] = $value;
}

/************ 测试 ****************/
// 测试时需要将与图片相关的代码注释
// $arr = array();
// $arr['title'] = "华为手机";
// $arr['category'] = "电子产品";
// $arr['brand'] = "华为";
// $arr['description'] = "华为p20，好手机";
// $arr['buy_price'] = "5000";
// $arr['buy_way'] = "京东";
// $arr['new'] = "1";
// $arr['price'] = "3000";
// $arr['bargain'] = "1";
// $arr['delivery'] = "0";
/*********** 测试 *****************/

// 1. 判断图片格式是否合法
$valid_ext = ['png', 'jpg', 'jpeg', 'bmp'];
$imgExt = array();
for ($i=0; $i<count($picArr); $i++) { 
	$imgName = $picArr[$i]['name'];
	$imgExt[$i] = pathinfo($imgName)['extension'];
	if(!in_array($imgExt[$i], $valid_ext)) {
		echo json_encode(['code' => 1, 'msg' => '参考图片格式不正确，不支持' . $imgExt[$i] . '类型']);
		exit;
	}
}

// 2. 图片重命名
$name = array();
$dest_dir = "market/picture/";		// 图片根目录
for($i=0; $i<count($picArr); $i++) {
	$name[$i] = time() . mt_rand(0, 10000) . "." . $imgExt[$i];		// 图片重命名
	while(file_exists("../../".$dest_dir.$name)) {
		// 如果这个文件名已经存在了
		$name[$i] = time() . mt_rand(0, 10000) . "." . $imgExt[$i];
	}
}

// 3. 图片另存为
for($i=0; $i<count($picArr); $i++) {
	$flag = move_uploaded_file($picArr[$i]['tem_name'], "../../". $dest_dir . $name[$i]);		// 保存图片
	if($flag === false) {
		echo json_encode(['code' => 0, 'msg' => '图片' . $picArr[$i]['name'] . '上传失败']);
		exit;
	}
}


// 4. 写入数据库
$db = new Db();
$user = $_SESSION['user'];
$sale = new sale();
$arr['email'] = $user->getEmail();
$arr['picture'] = "";
for($i=0; $i<count($picArr); $i++) {
	// 使用“;”将多个图片路径分隔
	$arr['picture'] .= ($dest_dir.$name[$i].";");
}
$arr['picture'] = substr($arr['picture'], 0, -1);
$arr['time'] = $time;
$sale->set($arr);
$db->insert("sale", $sale);
echo json_encode(['code' => 1, 'msg' => '发布成功']);
?>