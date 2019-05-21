<?php
/******************* 上传文件 ********************/
include_once("../../lib/Db.php");
include_once("../../lib/FileProcess.php");
include_once("../../entity/user.php");
include_once("../../lib/zip.php");
include_once("../../entity/file.php");

/**
 * 将文件文明重命名为一个全球唯一标识uuid，真正的文件名存在name字段中
 */

session_start();
define("FIRST_DIR", "../../upload_file/");

$file = $_FILES['file'];		// 获得上传的文件对象
$uploadFilename = $file['name'];	// 获取文件名（主要作用是获取文件的后缀名）
$errno = $file['error'];
if(!($errno == 0)) {
	// 上传文件出错
	echo json_encode(['code' => 0, 'msg' => '上传失败']);
	exit();
}

$user = $_SESSION['user'];		// 获得上传者的个人信息

// 获取文件的各种信息
$category 	 = $_POST['category'];			// 0课内/1课外
$subject	 = $_POST['subject'];			// 资料对应科目
$type		 = "";							// 资料分类
$time	 	 = "";							// 资料针对时间
$name		 = $_POST['name'];				// 资料名称
$teacher	 = "";							// 教师
$description = "";							// 资料描述信息
if($category == 0) {
	$type 		 = $_POST['type'];
	$time 		 = $_POST['time'];
	$teacher 	 = $_POST['teacher'];
} else {
	$description = $_POST['description'];
}
$upload_time = date("Y-m-d", time());		// 上传时间(年月日)
$id 		 = $user->getEmail();			// 上传者学号

$filename = "";
$ext = "";									// 文件类型
if(isset(pathinfo($uploadFilename)['extension'])) {
	$ext = pathinfo($uploadFilename)['extension'];
	$filename = "." . $ext;
	if($ext != "zip")
		$name = $name . "." . $ext;
} else {
	$ext = "other";
}


// 计算文件目录，保存文件
$second_dir = date("Y", time());
$third_dir = $subject;
$fourth_dir = $ext;
$subdir = $second_dir . "/" . $third_dir . "/" . $fourth_dir . "/";
FileProcess::createFolder(FIRST_DIR . $subdir);		// 创建文件夹

$realFilename = uuid() . $filename;			// 磁盘上存储的文件名
$flag = move_uploaded_file($file['tmp_name'], FIRST_DIR . $subdir . $realFilename);
if($flag === true) {
	// 上传成功
	$flag = ['code' => 1, 'msg' => '上传成功'];

	// 上传成功则写入数据库
	$arr = [
		"category" 		=> $category,
		"subject"  		=> $subject,
		"type"     		=> $type,
		"time"			=> $time,
		"description"	=> $description,
		"upload_time"	=> $upload_time,
		"filename"		=> $realFilename,
		"email"			=> $id,
		"path"			=> $subdir,
		"teacher"		=> $teacher,
		"name"			=> $name,
		"is_dir"		=> 0
	];
	$db = new Db();

	if($fourth_dir === "zip") {
		// 上传压缩包需要解压缩
		$dest_dir = FIRST_DIR . $subdir . substr($realFilename, 0, -4);

		$zip = new zip();
		$zip->decompress(FIRST_DIR . $subdir . $realFilename);

		// 解压完压缩包后需要将文件夹内的所有文件和文件夹信息写入数据库
		$file = new file();
		$arr['is_dir'] = 1;
		$arr['filename'] = substr($realFilename, 0, -4);
		$arr['size'] = FileProcess::getSize(FileProcess::getFolderSize($dest_dir));
		$file->set($arr);
		$db->insert("file", $file);

		store($dest_dir, $arr);
		unlink(FIRST_DIR . $subdir . $realFilename);		// 最后删除压缩包
	} else {
		// 仅仅是单个文件的上传
		$file = new file();
		$arr['size'] = FileProcess::getSize(filesize(FIRST_DIR . $subdir . $realFilename));
		$file->set($arr);
		$db->insert("file", $file);
	}
} else {
	$flag = ['code' => 0, 'msg' => '上传失败'];
}

echo json_encode($flag);

/**
 * 获取全球唯一标识uuid
 */
function uuid() { 
	$charid = strtoupper(md5(uniqid(mt_rand(), true))); 
	$hyphen = chr(45);// "-" 
	$uuid = chr(123)// "{" 
	.substr($charid, 0, 8).$hyphen 
	.substr($charid, 8, 4).$hyphen 
	.substr($charid,12, 4).$hyphen 
	.substr($charid,16, 4).$hyphen 
	.substr($charid,20,12) 
	.chr(125);// "}" 
	return $uuid; 
}

function store($dir, $arr) {
	global $db;
	$hander = opendir($dir);
	while(($filename = readdir($hander)) !== false) {
		if($filename != "." && $filename != "..") {
			if(is_dir($dir."/".$filename)) {
				$arr['filename'] = $filename;
				$arr['name'] = $filename;
				$arr['is_dir'] = 1;
				$arr['path'] = substr($dir, 18);
				$arr['size'] = FileProcess::getSize(FileProcess::getFolderSize($dir."/".$filename));

				$file = new file();
				$file->set($arr);
				$db->insert('file', $file);
				store($dir."/".$filename, $arr);
			} else {
				$arr['filename'] = $filename;
				$arr['name'] = $filename;
				$arr['is_dir'] = 0;
				$arr['path'] = substr($dir, 18);
				$arr['size'] = FileProcess::getSize(filesize($dir."/".$filename));

				$file = new file();
				$file->set($arr);
				$db->insert("file", $file);
			}
		}
	}
	closedir($hander);
}

?>