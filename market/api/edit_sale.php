<?php
/************ 编辑商品 *************/

include_once("../../entity/sale.php");
include_once("../../lib/Db.php");

/**
 * 接收参数：
 * 商品参数：data
 * 商品图片：picture
 * 商品状态：state    是否已经出售或者删除
 */

$id = $_GET['id'];
$data = isset($_GET['data']) ? $_GET['data'] : NULL;
$picture = isset($_FILES['picture']) ? $_FILES['picture'] : NULL;
$state = isset($_GET['state']) ? $_GET['state'] : NULL;

$db = new Db();
$sale = $db->select("sale", ['id' => $id]);

if($sale === NULL) {
	// 商品不存在
	echo json_encode(['code' => 0, 'msg' => '网络异常']);
}

if($data === NULL && $state === NULL) {
	echo json_encode(['code' => 1, 'msg' => '修改成功']);
	exit;
}

if($state !== NULL) {
	if($state == "0") {
		// 已出售
		$sale->setIs_sell(1);
	} else if($state == "1") {
		// 已删除
		$sale->setDelete(1);
	}
	$db->update("sale", $sale);
	echo json_encode(['code' => 1, 'msg' => '修改成功']);
	exit;
}

if($data !== NULL) {
	// 处理商品的描述信息
	$data = json_decode($data);
	foreach ($data as $key => $value) {
		$func = "set".ucfirst($key);
		$sale->$func($value);
	}

	// 处理商品的参考图片
	$dest_dir = "market/picture/";		// 图片根目录
	if($picture !== NULL) {
		$picArr = array();
		$name = array();
		$new = array();
		foreach ($picture as $key => $value) {
			$picArr[] = $value;
			$name[] = $value['name'];
		}

		$allPic = $sale->getPicture();
		$list = explode(";", $allPic);

		// 删除图片
		for($i=0;$i<count($list);$i++) {
			if(!in_array($list[$i], $name)) {
				// 需要删除原来的图片
				unlink("../../".$list[$i]);
			}
		}

		// 新增图片
		for($i=0;$i<count($name);$i++) {
			if(!in_array($name[$i], $list)) {
				// 需要新增加的图片
				$valid_ext = ['png', 'jpg', 'jpeg', 'bmp'];
				$imgExt = pathinfo($name)['extension'];
				if(!in_array($imgExt, $valid_ext)) {
					echo json_encode(['code' => 0, 'msg' => '图片'.$name[$i].'格式不正确']);
					exit;
				}

				$newName = time() . mt_rand(0, 10000) . "." . $imgExt;		// 图片重命名
				while(file_exists($dest_dir.$newName)) {
					$newName = time() . mt_rand(0, 10000) . "." . $imgExt;
				}
				$name[$i] = $newName;

				$flag = move_uploaded_file($picArr[$i]['tem_name'], "../../". $dest_dir . $newName);
				if($flag === false) {
					echo json_encode(['code' => 0, 'msg' => '图片' . $picArr[$i]['name'] . '上传失败']);
					exit;
				}
			}
		}

		$picture = "";
		for($i=0;$i<count($name);$i++) {
			$picture .= ($dest_dir.$name[$i].";");
		}
		$picture = substr($picture, 0, -1);
		$sale->setPicture($picture);
		$sale->setTime(time());
	}
	$db->update("sale", $sale);
	echo json_encode(['code' => 1, 'msg' => '修改成功']);
}


?>