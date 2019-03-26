<?php
/**************** 搜索文件 *********************/

include_once("../../lib/Db.php");
$db = new Db();
include_once("../../lib/PHPAnalysis/PHPAnalysis.class.php");
$analysis = new PhpAnalysis();
include_once("../../entity/file.php");

$key = $_GET['key'];		// 获取关键字
$type = $_GET['type'];		// 获取资料类型
$page = $_GET['[page'];		// 获取查询页码

$dir = "";			// 目标网页相对于根目录的路径，用于生成url
/**
 * 保存所有的查询结果，按相似度排序
 * 数组每个元素由一个键值对组成：学号+文件名+$+关键字=>文件对象集合
 * 相似度由查找文件时所用的关键字长度表示
 */
$data = array();

/**
 * 关键字索引数组
 */
$indexArray = array();

// $key = "Java资料";
// $type = "计算机";

// 1.查找最相似的文件
$res = $db->findFile($key, $type);
if(!($res === NULL)) {
	for($i=0;$i<count($res);$i++) {
		// 保存文件对象
		$fileIndex = $res[$i]->getId().$res[$i]->getFilename()."$".$key;
		$data[$fileIndex] = $res[$i];

		$indexArray[] = $fileIndex;		// 用索引保存关键字
	}	
}

// 2. 对关键字进行分词，然后对每个词语执行关键字搜索
$analysis->setSource($key);
$analysis->startAnalysis();
$words = $analysis->getFinallyResult(" ");
$keys = explode(" ", $words);

foreach ($keys as $key) {
	if(!($key === "")) {
		$res = $db->findFile($key, $type);
		if(!($res === NULL)) {
			for($i=0;$i<count($res);$i++) {
				// 保存文件对象
				$fileIndex = $res[$i]->getId().$res[$i]->getFilename()."$".$key;
				$data[$fileIndex] = $res[$i];

				$indexArray[] = $fileIndex;		// 用索引保存关键字
			}
		}
	}	
}

$newData = array();		// 新合并的文件(保存对应的索引即可：学号+文件名+$+关键字)
$delData = array();		// 要删除的文件(保存对应的索引即可：学号+文件名+$+关键字)


// 3. 判断不同相似度的文件是否相同。
// 如果有相同的文件名，且其中一类的关键字包含了另一类的关键字，则删除关键字较短的那一项;
// 如果文件名相同，并且是根据不同的关键字得到的，那么合并关键字得到新的一项
while(true) {
	for($i=0;$i<count($indexArray);$i++) {
		$index1 = $indexArray[$i];		// 根据索引值获取data数组的键
		$file1 = $data[$index1];		// 根据键获取对应的值

		// 截取文件名称和关键字
		$list = explode("$", $index1);
		$filename1 = $list[0];		// 学号+文件名
		$key1 = $list[1];			// 关键字

		for($j=$i+1;$j<count($indexArray);$j++) {
			$index2 = $indexArray[$j];
			$file2 = $data[$index2];

			// 截取文件名和关键字
			$list = explode("$", $index2);
			// $filename2 = $list[0];		// 学号+文件名
			$key2 = $list[1];			// 关键字

			// 如果文件相同且关键字不同
			if($file1 == $file2 && $key1 <> $key2) {
				// 先判断包含关系，如果成立则执行删除操作
				if(strstr($key1, $key2) === false) {
					if(strstr($key2, $key1) === false) {}
					else {
						// 文件2的关键字包含文件1，删除文件1
						$delData[] = $index1;
						continue;
					}
				} else {
					// 文件1的关键字包含文件2的关键字，删除文件2
					$delData[] = $index2;
					continue;
				}

				if(in_array($index1, $delData) || in_array($index2, $delData)) {
					// 如果这两个文件中有一个已经在删除队列中，那么就没必要进行合并操作
				} else {
					// 没有包含关系，执行合并操作
					$newKey = $key1.$key2;
					$newData[$filename1."$".$newKey] = $file1;
				}				
			}
		}
	}

	// 如果既没有要删除也没有新增的文件，则整个集合收敛了，跳出循环
	if(count($delData) === 0 && count($newData) === 0) {
		break;
	}

	// 一轮比对结束，删除对应的文件
	for($i=0;$i<count($data);$i++) {
		$index = $indexArray[$i];
		if(in_array($index, $delData)) {
			// 这个文件要删除
			unset($data[$index]);

			// 这个关键字索引也需要删除
			unset($indexArray[$i]);
		}
	}
	$indexArray = array_values($indexArray);	// 对索引数组进行重排索引

	// 增加新的文件
	foreach ($newData as $key => $value) {
		// 增加新文件
		$data[$key] = $value;

		// 增加新的关键字索引
		$indexArray[] = $key;
	}

	// 清零delData和newData
	$delData = [];
	$newData = [];
}

// 4. 按关键字长度进行排序,对于关键字一样长的按推荐值进行排序
for($i=0;$i<count($data);$i++) {
	$index1 = $indexArray[$i];
	$list = explode("$", $index1);
	$key1 = $list[1];	// 关键字1

	for($j=$i+1;$j<count($data);$j++) {
		$index2 = $indexArray[$j];
		$list = explode("$", $index2);
		$key2 = $list[1];	// 关键字2

		if(strlen($key2) > strlen($key1)) {
			// 交换索引
			$temp = $indexArray[$i];
			$indexArray[$i] = $indexArray[$j];
			$indexArray[$j] = $temp;

			// 同时交换对象
			$key1 = $key2;
			$index1 = $index2;
		} else if(strlen($key1) === strlen($key2)) {
			// 关键字长度相等
			$recommend1 = getRecommend($data[$index1]);
			$recommend2 = getRecommend($data[$index2]);
			if($recommend2 > $recommend1) {
				// 交换索引
				$temp = $indexArray[$i];
				$indexArray[$i] = $indexArray[$j];
				$indexArray[$j] = $temp;

				// 同时交换对象
				$key1 = $key2;
				$index1 = $index2;
			}
		}
	}
}

// 5. 将排序结果进行分页(每页10个)
$json_data = array();
$json_index = 0;

for($i=0;$i<count($data);$i++) {
	$index = $indexArray[$i];
	$file = $data[$index];

	$name 			= $file->getFilename();
	$category 		= $file->getCategory();
	$subject 		= $file->getSubject();
	$type 			= $file->getType();
	$time 			= $file->getTime();
	$description 	= $file->getDescription();
	$upload_time 	= $file->getUpload_time();
	$upload_user 	= $file->getId();			// 暂时先用学号代表上传人
	$like 			= $file->getLike();
	$dislike 		= $file->getDislike();
	$download 		= $file->getDownload();
	$path			= $file->getPath();
	$size			= filesize(dirname(__FILE__)."/../../".$name);		// 此处有待验证


	$json_data[$json_index][] = [
			"name" 			=> $name,
			"category" 		=> $category,
			"subject" 		=> $subject,
			"type" 			=> $type,
			"time" 			=> $time,
			"description" 	=> $description,
			"upload_time" 	=> $upload_time,
			"upload_user" 	=> $upload_user,
			"like"			=> $like,
			"dislike" 		=> $dislike,
			"download" 		=> $download
			"url"			=> $dir."/".$path,
			"size"			=> $size
		];

	if($i%10 === 9) {
		$json_index++;
	}
}

if(count($json_data) === 0) {
	echo json_encode(['code' => 0, 'msg' => '没有找到您需要的文件']);
} else {
	if($page < count($json_data)) {
		echo json_encode(['code' => 1, "data" => $json_data[$page-1])
	} else {
		echo json_encode(['code' => 0, 'msg' => '该页不存在']);
	}
}

/**
 * 计算文件的推荐值
 */
function getRecommend($file) {
	$like = $file->getLike();
	$dislike = $file->getDislike();
	$download = $file->getDownload();

	return $like - 2*$dislike + $download;
}

?>