<?php
/************* 搜索商品 ****************/
include_once("../../lib/Db.php");
include_once("../../lib/PHPAnalysis/PHPAnalysis.class.php");
include_once("../../entity/sale.php");

/**
 * 搜索步骤：
 *	1. 将所有符合条件的商品全部搜索出来
 *	2. 对包含关键字的相同商品进行删除，选取最长关键字
 *	3. 对不同关键字的相同商品进行合并，得到新的关键字
 *	4. 按关键字长度进行排序
 *	5. 分页
 *
 * 关键字搜索包括：商品标题、商品描述
 * 筛选条件包括：
 * 排序条件包括：
 */

$key = $_GET['key'];
$page = $_GET['page'];
$filter = $_GET['filter'];
$sort = $_GET['sort'];

$db = new Db();
$keywords = array();
$goods = array();

// 1.1 完全匹配搜索(没有加入筛选条件)
search($key);

// 1.2 分词匹配搜索
$keys = getToken($key);
foreach ($keys as $key) {
	if($key !== "") {
		search($key);
	}
}

// 2. 排序

// 3. 分页
$json_data = page();
if(count($json_data) === 0) {
	echo json_encode(['code' => 0, 'msg' => '没有找到您需要的商品']);
} else {
	if($page <= count($json_data)) {
		echo json_encode(['code' => 1, "data" => $json_data[$page-1], "amount" => count($goods)]);
	} else {
		echo json_encode(['code' => 0, 'msg' => '该页不存在']);
	}
}


/************ 函数定义 *************/

/**
 * 关键字搜索
 */
function search($key) {
	global $db;
	$search = ["title", "description"];
	for($i=0;$i<count($search);$i++) {
		$res = $db->find_goods($key, $search[$i]);
		if($res === NULL) continue;
		for($j=0;$j<count($res);$j++) {
			store($key, $res[$j]);
		}
	}
}

/**
 * 保存商品信息
 * @param $key 商品关键字
 * @param $good 商品信息
 */
function store($key, $good) {
	global $keywords, $goods;
	$index = array_search($good, $goods);
	if($index === false) {
		// 之前没有保存过这个商品信息
		$keywords[] = $key;
		$goods[] = $good;
	} else {
		// 对于关键字互相包含的取长舍短，对于不包含的则进行合并
		$oldKey = $keywords[$index];
		if(strstr($key, $oldKey) !== false) {
			// 新关键字包含旧的关键字，进行替换
			$keywords[$index] = $key;
		} else if(strstr($oldKey, $key) === false) {
			// 关键字不互相包含，则进行合并
			$newKey = $oldKey.$key;
			$keywords[$index] = $newKey;
		}
	}
}

/**
 * 分词函数，将传入的字符串分词
 * @param $key 字符串
 * @param 分词后的字符串数组
 */
function getToken($key) {
	$analysis = new PhpAnalysis();
	$analysis->setSource($key);
	$analysis->startAnalysis();
	$words = $analysis->getFinallyResult(" ");
	$keys = explode(" ", $words);
	return $keys;
}

/**
 * 分页
 */
function page() {
	global $goods;

	$json_data = array();
	$json_index = 0;

	for($i=0;$i<count($goods);$i++) {
		$sale = $goods[$i];

		$id = $sale->getId();
		$title = $sale->getTitle();
		$picture = $sale->getPicture();
		$pic = explode(';', $picture);
		$price = $sale->getPrice();
		$view = $sale->getView();

		$json_data[$json_index][] = [
				'id' 	=> $id,
				'title' => $title,
				'pic' 	=> $pic,
				'price' => $price,
				'view' 	=> $view
		];

		if($i%10 === 9) {
			$json_index++;
		}
	}
	return $json_data;
}
?>