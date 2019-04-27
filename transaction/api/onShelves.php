<?php
/*********** 上架商品 *************/
include_once("../../lib/Db.php");
include_once("../../entity/user.php");
include_once("../../entity/goods.php");
session_start();

// $isSell = $_GET['isSell'];		// 收购还是出售(1为出售 0为收购)
// $name = $_GET['name'];			// 商品名称
// $price = $_GET['price'];		// 预期价格
// $picture = NULL;				// 商品图片
// $description = $_GET['description'];	// 商品描述

// $isSell = "0";		// 收购还是出售(1为出售 0为收购)
// $name = "嵌入式Linux完全开发手册";			// 商品名称
// $price = "100.01";		// 预期价格
// $picture = NULL;				// 商品图片
// $description = "最近在学嵌入式系统，需要一份嵌入式Linux开发手册，电子版和实体书都可以";	// 商品描述

if($isSell == "1") {	// 只有出售时才需要图片
	$picture = $_FILES['picture'];

	/******** 图片另存transaction/sellImage/ ***********/
	$dest_dir = "transaction/sellImage/";

	// 1. 检测是否为图片
	$exts = ["jpg", "jpge", "png", "bmp"];
	$imgName = $picture['name'];
	$imgExt = pathinfo($imgName)['extension'];
	if(!in_array($imgExt, $exts)) {
		echo json_encode(['code' => 0, 'msg' => '参考图片格式不正确']);
		exit;
	}

	// 2. 对图片进行重命名，用时间+随机数命名法
	$name = time() . mt_rand(0, 10000) . "." . $imgExt;
	while(file_exists("../../".$dest_dir.$name)) {
		// 如果这个文件名已经存在了
		$name = time().mt_rand(0, 10000) . "." . $imgExt;
	}

	// 3. 保存图片
	$flag = move_uploaded_file($picture['tem_name'], "../../". $dest_dir . $name);
	if($flag === false) {
		echo json_encode(['code' => 0, 'msg' => '图片上传失败']);
		exit;
	}

	$picture = $dest_dir.$name;
}

$email = $_SESSION['user']->getEmail();

$db = new Db();
$goods = new goods();
$goods->set(['email' => $email, 'name' => $name, 'price' => $price, 'picture' => $picture, 'description' => $description, 'isSell' => $isSell, 'time' => time()]);
$db->insert("goods", $goods);

echo json_encode(['code' => 1, 'msg' => "上架成功"]);
?>