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

// $key = $_GET['key'];
// $page = $_GET['page'];
// $filter = $_GET['filter'];
// $sort = $_GET['sort'];

$key = "手机";
$page = 1;

$db = new Db();
$analysis = new PhpAnalysis();


$data = array();
$json_data = array();
$indexArray = array();

// 1.1 完全匹配搜索
search($key);

// 1.2 分词匹配搜索
$keys = getToken($key);
foreach ($keys as $key) {
	if($key !== "") {
		search($key);
	}
}

// 2. 删除重复合并不同
delAndNew();

// 3. 排序

// 4. 分页
$json_data = page();
if(count($json_data) === 0) {
	echo json_encode(['code' => 0, 'msg' => '没有找到您需要的商品']);
} else {
	if($page <= count($json_data)) {
		echo json_encode(['code' => 1, "data" => $json_data[$page-1], "amount" => count($data)]);
	} else {
		echo json_encode(['code' => 0, 'msg' => '该页不存在']);
	}
}


/************ 函数定义 *************/

/**
 * 关键字搜索
 */
function search($key) {
	global $db, $data, $indexArray;
	// title
	$res = $db->find("sale", $key, "title", false);
	if($res !== NULL) {
		for($i=0;$i<count($res);$i++) {
			$saleIndex = $res[$i]->getId() . "$" . $key;
			if(!in_array($saleIndex, $indexArray)) {
				$data[$saleIndex] = $res[$i];
				$indexArray[] = $saleIndex;
			}
		}
	}
	

	// description
	$res = $db->find("sale", $key, "description", false);
	if($res !== NULL) {
		for($i=0;$i<count($res);$i++) {
			$saleIndex = $res[$i]->getId() . "$" . $key;
			if(!in_array($saleIndex, $indexArray)) {
				$data[$saleIndex] = $res[$i];
				$indexArray[] = $saleIndex;
			}
		}
	}	
}

/**
 * 分词函数，将传入的字符串分词
 * @param $key 字符串
 * @param 分词后的字符串数组
 */
function getToken($key) {
	global $analysis;
	$analysis->setSource($key);
	$analysis->startAnalysis();
	$words = $analysis->getFinallyResult(" ");
	$keys = explode(" ", $words);
	return $keys;
}

/**
 * 删除重复合并不同
 */
function delAndNew() {
	global $indexArray, $data;

	$delData = array();		// 要删除的文件
	$newData = array();		// 新合并的文件

	while(true) {
		for($i=0;$i<count($indexArray);$i++) {
			$index1 = $indexArray[$i];
			$sale1 = $data[$index1];

			$list = explode("$", $index1);
			$id1 = $list[0];
			$key1 = $list[1];

			for($j=$i+1;$j<count($indexArray);$j++) {
				$index2 = $indexArray[$j];
				if(in_array($index2, $delData)){
					continue;
				}

				$sale2 = $data[$index2];

				$list = explode("$", $index2);
				$id2= $list[0];
				$key2 = $list[1];

				if($id1 === $id2) {		// 同一个商品
					
					// 删除重复
					if(strstr($key1, $key2) === false) {
						if(!(strstr($key2, $key1) === false)) {
							// 关键字2包含关键字1
							if(!in_array($index1, $delData)) {
								$delData[] = $index1;
							}
							continue;
						}
					} else {
						// 关键字1包含关键字2
						if(!in_array($index2, $delData)) {
							$delData[] = $index2;
						}
						continue;
					}

					// 合并不同
					$newKey = $key1.$key2;
					$newData[$id1."$".$newKey] = $sale1;
					if(!in_array($index1, $delData)) {
						$delData[] = $index1;
					}
					if(!in_array($index2, $delData)) {
						$delData[] = $index2;
					}
				}
			}
		}
		// 如果既没有要删除也没有新增的文件，则整个集合收敛了，跳出循环
		if(count($delData) === 0 && count($newData) === 0) {
			break;
		}

		// 一轮比对结束，删除对应的文件
		for($i=0;$i<count($delData);$i++) {
			$index = $delData[$i];
			unset($data[$index]);
			unset($indexArray[array_search($index, $indexArray)]);
		}
		
		$indexArray = array_values($indexArray);	// 对索引数组进行重排索引

		// 增加新的文件
		foreach ($newData as $key => $value) {
			$data[$key] = $value;	// 增加新文件
			$indexArray[] = $key;	// 增加新的关键字索引
		}

		// 清零delData和newData
		$delData = [];
		$newData = [];
	}
}

/**
 * 分页
 */
function page() {
	global $db, $data, $indexArray;

	$json_data = array();
	$json_index = 0;

	for($i=0;$i<count($data);$i++) {
		$index = $indexArray[$i];
		$sale = $data[$index];

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