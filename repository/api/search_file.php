<?php
/**************** 搜索文件 ********************/

/**
 * 1. 根据输入的关键字进行完全匹配搜索和分词匹配搜索(搜索目标：文件名、资料名、路径名、文件描述、科目)
 * 2. 根据既定的排序规则进行排序
 * 3. 进行分页
 *
 * 进行搜索时，需要将搜索结果保存，应该用一个键值对进行保存（key=关键字，value=文件信息）
 * 由于一个关键字能搜索出多个文件，所以会出现覆盖导致错误，所以将关键字和文件信息分开存储，用两个数组保存，只需要保证数组的索引相同即可
 * 由于一个文件可以由不同的关键字搜索得到，所以我们要选择最佳的关键字保存。在搜索过程中对于同一个文件，如果关键字互相包含则取长舍短，关键字不互相包含则进行合并
 */

include_once("../../lib/Db.php");
include_once("../../entity/file.php");
include_once("../../lib/Analysis.php");
include_once("../../entity/user.php");

$root = "../../";

// 参数：关键字key、搜索模式mode(文件搜索/文件夹搜索)、排序方式sort(相关度、下载量、上传时间、评分)、显示页码page
$key = $_GET['key'];
$mode = $_GET['mode'];
$sort = $_GET['sort'];
$page = $_GET['page'];
// $key = "操作系统08春季";
// $mode = "1";
// $sort = "3";
// $page = "1";

// 用于保存关键字和文件信息的数组
$keywords = array();
$files = array();

$db = new Db();
$onlyFile = $mode==0?true:false;

// 搜索
search($key, $onlyFile);
$keys = getToken($key);
for($i=0;$i<count($keys);$i++) {
	if($keys[$i] !== "") {
		search($keys[$i], $onlyFile);
	}
}

// 排序
if($sort == 0) {
	// 相关度排序
	for($i=0;$i<count($keywords);$i++) {
		$key = $keywords[$i];
		for($j=$i;$j<count($keywords);$j++) {
			if(strlen($key) < strlen($keywords[$j])) {
				$key = $keywords[$j];
				change($i, $j);
			}
		}
	}
} else if($sort == 1) {
	// 下载量排序
	for($i=0;$i<count($keywords);$i++) {
		$download = $files[$i]->getDownload();
		for($j=$i;$j<count($keywords);$j++) {
			if($download < $files[$j]->getDownload()) {
				$download = $files[$j]->getDownload();
				change($i, $j);
			}
		}
	}
} else if($sort == 2) {
	// 上传时间排序
	for($i=0;$i<count($keywords);$i++) {
		$upload_time = strtotime($files[$i]->getUpload_time());
		for($j=$i;$j<count($keywords);$j++) {
			if($upload_time < strtotime($files[$j]->getUpload_time())) {
				$upload_time = strtotime($files[$j]->getUpload_time());
				change($i, $j);
			}
		}
	}
} else if($sort == 3) {
	// 评分排序
	for($i=0;$i<count($keywords);$i++) {
		$score = $files[$i]->getScore();
		for($j=$i;$j<count($keywords);$j++) {
			if($score < $files[$j]->getScore()) {
				$score = $files[$j]->getScore();
				change($i, $j);
			}
		}
	}
}

// 分页
$json_data = array();
$json_index = 0;

for($i=0;$i<count($files);$i++) {
	$file = $files[$i];

	$name			= $file->getName();
	$category 		= $file->getCategory();
	$subject 		= $file->getSubject();
	$type 			= $file->getType();
	$time 			= $file->getTime();
	$description 	= $file->getDescription();
	$upload_time 	= $file->getUpload_time();
	$upload_uid 	= $file->getEmail();											
	$upload_uname	= $db->select("user", ['email' => $upload_uid])->getUsername();	// 上传人名称
	$score			= $file->getScore();
	$download 		= $file->getDownload();
	$path			= $file->getPath();
	$filename 		= $file->getFilename();
	$is_dir			= $file->getIs_dir();
	$size			= $file->getSize();
	$contents 		= "";
	$url			= "";
	
	if($onlyFile === false) {
		// 需要生成内部目录结构
		$dir = new dirctory("../../upload_file/".$path."/".$filename);
		$contents = $dir->getContents()[$filename];
		$url = $path . $filename;
	} else{
		$url = $path . "/" . $filename;
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
		"url"			=> $url,
		"size"			=> $size,
		"contents"		=> $contents
	];

	if($i%10 === 9) {
		$json_index++;
	}
}

if(count($json_data) === 0) {
	echo json_encode(['code' => 0, 'msg' => '没有找到您需要的文件']);
} else {
	if($page <= count($json_data)) {
		echo json_encode(['code' => 1, "data" => $json_data[$page-1], "amount" => count($files)]);
	} else {
		echo json_encode(['code' => 0, 'msg' => '该页不存在']);
	}
}

/**************** 函数定义 ********************/
function search($key, $onlyFile=true) {
	global $db;

	$search = ["filename", "name", "path", "description", "subject"];

	for($i=0;$i<count($search);$i++) {
		$res = $db->find_files($key, $search[$i], $onlyFile);		// 根据文件名搜索
		if($res === NULL) continue;

		if($onlyFile) {
			// 如果只搜索单个文件
			for($j=0;$j<count($res);$j++) {
				store($key, $res[$j]);
			}
		} else {
			// 只搜索顶层文件夹(year/subject/zip)
			for($j=0;$j<count($res);$j++) {
				$path = $res[$j]->getPath();
				if($path[strlen($path)-1] == "/") {
					store($key, $res[$j]);
				}
			}
		}
	}
}

/**
 * 保存关键字和文件对象
 * @param $key 关键字
 * @param $file 文件对象
 */
function store($key, $file) {
	global $keywords, $files;
	// 如果关键字互相包含则取长舍短，如果不互相包含则进行合并
	$index = array_search($file, $files);
	if($index === false) {
		// 之前没有保存过此文件
		$keywords[] = $key;
		$files[] = $file;
	} else {
		// 之前保存过此文件，则选择最优的关键字
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
 * 交换关键字->文件对象在数组中的位置
 * @param $i
 * @param $j
 */
function change($i, $j) {
	global $keywords, $files;

	$temp = $keywords[$i];
	$keywords[$i] = $keywords[$j];
	$keywords[$j] = $temp;

	$temp = $files[$i];
	$files[$i] = $files[$j];
	$files[$j] = $temp;
}

/************ 类定义 **************/
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

	public function getContents() {
		$contents = [];
		foreach ($this->dirs as $key => $value) {
			$contents[$this->path] = $value->getContents();
		}

		for($i=0;$i<count($this->files);$i++) {
			$contents[$this->path][$this->files[$i]] = 0;
		}

		return $contents;
	}
}
?>