<?php
/*********** 出售物品 ***************/
session_start();
include_once("../../lib/Db.php");
include_once("../../entity/sell.php");
$db = new Db();

/**
 * 需求参数：
 * 1. 物品名称
 * 2. 物品图片
 * 3. 物品描述
 * 4. 预期价格
 */
$objName = "";
$objPicture = "";		// 图片文件，需要保存到专门的路径下
$objDescription = "";
$price = 0;

$user = $SESSION['user'];
$email = $user->getEmail();

/******** 保存图片 *********/

/****** 计算图片路径 *******/

$obj = new sell();
$obj->set(['email' => $email, 'objName' => $objName, 'objPicture' => $objPicture, 'objDescription' => $objDescription, 'price' => $price]);
$db->insert("sell", $obj);
?>