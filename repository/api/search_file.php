<?php
/**************** 搜索文件 *********************/
/**
 * 根据文件名、路径名、科目名称、文件描述进行搜索
 * 搜索的结果包括单个文件，也包括文件夹。此后我将文件夹也视作一个文件对象，所以后面我所提到的所有文件都包含文件夹的含义
 *
 * 搜索过程分为两个步骤：
 * 	|- 完全匹配搜索: 直接在上述四类名称中根据关键字进行完全匹配
 *	|- 分词匹配搜索: 对关键字进行分词，对分词的每一部分进行匹配
 *
 * 匹配过程中将文件与关键字建立起对应关系
 * 由于同一个文件可以对应多个关键字，所以直接采用关联数组无法描述
 * 而之后需要使用双重for循环对文件进行排序，这要求这种对应关系是索引数组
 * 可以在存储时进行如下设计
 *	|- 用关联数组保存文件和关键字对应关系
 *		|- key=学号+文件名+关键字
 *		|- value=文件对象
 *	|- 利用索引数组存储key(学号+文件名+关键字)
 *
 * 如果一个文件对应于多个关键字，则进行相应的合并删除操作
 *	|- 如果关键字a包含关键字b，则删除关键字b对应的项
 *	|- 如果关键字a和关键字b没有包含关系，则将两者合并成新的一项，并且删除这两项
 *
 * 根据关键字的长度进行排序，对于关键字长度相同的则根据点赞量、差评量和下载量进行排序
 *	|- 采用冒泡排序
 * 
 * 进行分页处理，每10项一页，最后不足10项算一页
 *	|- 使用二维数组进行存储
 */

include_once("../../lib/Db.php");
include_once("../../lib/PHPAnalysis/PHPAnalysis.class.php");
include_once("../../entity/file.php");
include_once("../../entity/user.php");

$db = new Db();
$analysis = new PhpAnalysis();

$root = dirname(__FILE__) . "/../../";

// $key = $_GET['key'];		// 获取关键字
// $page = $_GET['page'];		// 获取查询页码
// $mode = $_GET['mode'];		// 搜索模式
$key = "java";
$page = 1;
$mode = 0;

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

$flag = $mode===0 ? true : false;

// 1.1、完全匹配搜索
search($key, $flag);

// 1.2、分词匹配搜索
$keys = getToken($key);
foreach ($keys as $key) {
	if($key !== "") {
		search($key, $flag);
	}
}

// 2、删除重复
delAndNew();

// 3、排序
file_sort();

// 4、分页
$json_data = page();
if($mode === 1) {
	// 重构目录结构
	for($i=0;$i<count($json_data);$i++) {
		$files = $json_data[$i];
		for($j=0;$j<count($files);$j++) {
			$arr = $files[$j];

			$dir = $root.$arr['url'];
			if(is_dir($dir)) {
				$obj = new dirctory($dir);
				$arr['contents'] = $obj->getContents();
				$json_data[$i][$j]['contents'] = $arr['contents'];
			}
		}
	}
}

if(count($json_data) === 0) {
	echo json_encode(['code' => 0, 'msg' => '没有找到您需要的文件']);
	// var_dump(['code' => 0, 'msg' => '没有找到您需要的文件']);
} else {
	if($page <= count($json_data)) {
		echo json_encode(['code' => 1, "data" => $json_data[$page-1]]);
		// var_dump(['code' => 1, "data" => $json_data[$page-1]]);
	} else {
		echo json_encode(['code' => 0, 'msg' => '该页不存在']);
		// var_dump(['code' => 0, 'msg' => '该页不存在']);
	}
}

/************* 函数定义 ***************/

/**
 * 保存文件对象和关键字的关系
 * @param $res 文件对象数组
 * @param $key 关键字
 */
function store0($res, $key) {
	if($res !== NULL) {
		global $data, $indexArray;
		for($i=0;$i<count($res);$i++) {
			$fileIndex = $res[$i]->getId(). $res[$i]->getFilename(). "$". $key;
			if(!(in_array($fileIndex, $indexArray))) {
				$data[$fileIndex] = $res[$i];
				$indexArray[] = $fileIndex;
			}
		}
	}
}

function store1($res, $key) {
	if($res !== NULL) {
		global $data, $indexArray;
		for($i=0;$i<count($res);$i++) {
			// 判断是否为顶层文件或文件夹
			$path = $res[$i]->getPath();
			if(count(explode("/", $path)) === 4) {
				$fileIndex = $res[$i]->getId(). $res[$i]->getFilename(). "$". $key;
				if(!(in_array($fileIndex, $indexArray))) {
					$data[$fileIndex] = $res[$i];
					$indexArray[] = $fileIndex;
				}
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
 * 根据关键字对文件名、路径名、科目名、描述进行一轮搜索
 * @param $key 关键字
 */
function search($key, $onlyFile=true) {
	global $db;

	$res = $db->findFile($key, "filename", $onlyFile);		// 根据文件名搜索
	if($onlyFile === false) {
		store1($res, $key);
	} else {
		store0($res, $key);
	}

	$res = $db->findFile($key, "path", $onlyFile);		// 根据路径名搜索
	if($onlyFile === false) {
		store1($res, $key);
	} else {
		store0($res, $key);
	}

	$res = $db->findFile($key, "subject", $onlyFile);	// 根据科目搜索
	if($onlyFile === false) {
		store1($res, $key);
	} else {
		store0($res, $key);
	}

	$res = $db->findFile($key, "description", $onlyFile);	// 根据文件描述进行搜索
	if($onlyFile === false) {
		store1($res, $key);
	} else {
		store0($res, $key);
	}
}

/**
 * 删除和合并
 */
function delAndNew() {
	global $data, $indexArray;

	$newData = array();		// 新合并的文件(保存对应的索引即可：学号+文件名+$+关键字)
	$delData = array();		// 要删除的文件(保存对应的索引即可：学号+文件名+$+关键字)

	while(true) {
		for($i=0;$i<count($indexArray);$i++) {
			$index1 = $indexArray[$i];
			$file1 = $data[$index1];

			$list = explode("$", $index1);
			$filename1 = $list[0];
			$key1 = $list[1];

			for($j=$i+1;$j<count($indexArray);$j++) {
				$index2 = $indexArray[$j];
				if(in_array($index2, $delData)) {
					continue;
				}
				$file2 = $data[$index2];

				$list = explode("$", $index2);
				$filename2 = $list[0];
				$key2 = $list[1];

				if($filename1 === $filename2) {
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
					$newkey = $key1.$key2;
					$newData[$filename1."$".$newkey] = $file1;
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

function file_sort() {
	global $data, $indexArray;

	for($i=0;$i<count($indexArray);$i++) {
		$index1 = $indexArray[$i];
		$list = explode("$", $index1);
		$key1 = $list[1];

		for($j=$i+1;$j<count($indexArray);$j++) {
			$index2 = $indexArray[$j];
			$list = explode("$", $index2);
			$key2 = $list[1];

			if(strlen($key2) > strlen($key1)) {
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

function page() {
	global $data, $indexArray, $db;

	$json_data = array();
	$json_index = 0;

	for($i=0;$i<count($data);$i++) {
		$index = $indexArray[$i];
		$file = $data[$index];

		$name			= $file->getFilename();
		$category 		= $file->getCategory();
		$subject 		= $file->getSubject();
		$type 			= $file->getType();
		$time 			= $file->getTime();
		$description 	= $file->getDescription();
		$upload_time 	= $file->getUpload_time();
		$upload_uid 	= $file->getId();											// 暂时先用学号代表上传人
		$upload_uname	= $db->select("user", ['id' => $upload_uid])->getUsername();	// 上传人名称
		$score			= $file->getScore();
		$download 		= $file->getDownload();
		$path			= $file->getPath();
		$size			= 0;

		$len = count(explode("/", $path));
		if($len > 5) {
			// 这是一个压缩包中的文件
			$size = filesize(dirname(__FILE__)."/../../".$path."/".$name);
			$path .= "/{$name}";
		} else {
			$size = filesize(dirname(__FILE__)."/../../".$path."/".$upload_uid."_".$name);
			$path .= "/{$upload_uid}_{$name}";
		}

		$json_data[$json_index][] = [
				"name" 			=> $name,
				"category" 		=> $category,
				"subject" 		=> $subject,
				"type" 			=> $type,
				"time" 			=> $time,
				"description" 	=> $description,
				"upload_time" 	=> $upload_time,
				"upload_uid" 	=> $upload_uid,
				"upload_uname"	=> $upload_uname,
				"score"			=> $score,
				"download" 		=> $download,
				"url"			=> $path,
				"size"			=> $size,
				"contents"		=> ""
			];

		if($i%10 === 9) {
			$json_index++;
		}
	}

	return $json_data;
}

class dirctory {
	private $dirs = [];
	private $files = [];
	private $path;

	public function __construct($path) {
		$list = explode("/", $path);
		$this->path = $list[count($list)-1];

		// 根据文件夹路径生成目录结构
		$hander = opendir($path);
		while(($filename = readdir($hander)) !== false) {
			if($filename != "." && $filename != "..") {
				if(is_dir($path."/".$filename)) {
					$this->dirs[$filename] = new dirctory($path.'/'.$filename);
				} else {
					$this->files[] = $filename;
				}
			}
		}
	}

	public function print() {
		foreach ($this->dirs as $key => $value) {
			echo $key."<br/>";
			$value->print();
		}

		for($i=0;$i<count($this->files);$i++) {
			echo $this->files[$i]."<br/>";
		}
	}

	public function getContents() {
		$contents = [];
		foreach ($this->dirs as $key => $value) {
			$contents[$this->path] = $value->getContents();
		}

		for($i=0;$i<count($this->files);$i++) {
			$contents[$this->path][] = $this->files[$i];
		}

		return $contents;
	}
}

?>