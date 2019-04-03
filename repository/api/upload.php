<?php
/****************** 接收上传的文件 **********************/
include_once("../../lib/isLogin.php");	
include_once("../../entity/file.php");
include_once("../../entity/user.php");
include_once("../../lib/Db.php");
include_once("../../lib/zip.php");
if(isLogin() === false) {
	// 判断是否登录
	// header("Location:../../forbidden.html");	// 没有登陆无法上传文件，重定向到forbidden页面
	header("Location: ../../");
}


define("FIRST_DIR", "../../upload_file/");

$file = $_FILES['file'];			// 获取上传的文件
$filename = $file['name'];			// 获取上传文件的文件名

$errno = $file['error'];
if(!($errno == 0)) {
	// 上传文件出错
	echo ['code' => 0, 'msg' => '上传失败'];
	exit();
}

// 上传文件无误
$user = $_SESSION['user'];			// 获取上传者信息

// 获取文件的各种信息
$category 	 = $_POST['category'];			// 0课内/1课外
$subject 	 = $_POST['subject'];			// 资料对应科目
$type 		 = $_POST['type'];				// 资料分类
$time 		 = $_POST['time'];				// 资料针对时间
$description = $_POST['description'];		// 资料描述信息
$upload_time = date("Y-m-d", time());		// 上传时间(年月日)
$name	 	 = $_POST['name'];				// 资料名称
$id 		 = $user->getId();				// 上传者学号

/*********** 测试参数 ************
$id = "160400423";
$subject = "计算机";
$filename = "six.txt";
/*********** 测试参数 ************/

/**
 * 保存上传的文件
 * 一级目录(upload_file)
 * 二级目录（上传时间的年份）
 * 三级目录（科目）
 * 四级目录（文件类型，后缀名）
 */
$dest_dir = FIRST_DIR;
// 生成多级目录
$second_dir = date("Y", time());
$dest_dir .= $second_dir;
if(!file_exists($dest_dir)) {
	mkdir($dest_dir);
}

$third_dir = $subject;
$dest_dir .= "/{$third_dir}";
if(!file_exists($dest_dir)) {
	mkdir($dest_dir);
}

$fourth_dir = pathinfo($filename)['extension'];
$dest_dir .= "/{$fourth_dir}";
if(!file_exists($dest_dir)) {
	mkdir($dest_dir);
}

if(substr($filename, 0, -4) != $name) {
	// 资料名称和真正上传的文件名称不相同
	$filename = $name.".".pathinfo($filename)['extension'];
}
$name = $id."_".$filename;		// 文件重命名
$path = substr($dest_dir, 6);	// 文件路径(以网站根目录为起点)

$flag = move_uploaded_file($file['tmp_name'], $dest_dir."/".$name);			// 保存文件

if($flag === true) {
	// 上传成功
	$flag = ['code' => 1, 'msg' => '上传成功'];
}

// 将上传的文件信息写入数据库
$arr = [
	"category" 		=> $category,
	"subject"  		=> $subject,
	"type"     		=> $type,
	"time"			=> $time,
	"description"	=> $description,
	"upload_time"	=> $upload_time,
	"filename"		=> $filename,
	"id"			=> $id,
	"path"			=> $path
];
$db = new Db();

// 如果上传的文件是zip压缩包，解压缩并且将其中的所有文件信息都存入数据库
if($fourth_dir === "zip"){
	$zip = new zip();
	$zip->decompress($dest_dir."/".$name);
	$dir = substr($name, 0, -4);

	$file = new file();
	$arr['filename'] = substr($filename, 0, -4);
	$file->set($arr);
	$db->insert("file", $file);

	store($dest_dir."/".$dir, $arr, $db);
	unlink($dest_dir."/".$name);
} else {
	$newFile = new file();
	$newFile->set($arr);
	$db->insert("file", $newFile);
}

echo json_encode($flag);

/**
 * 递归保存文件和文件夹
 */
function store($dir, $arr, $db) {
	$hander = opendir($dir);
	while(($filename = readdir($hander)) !== false) {
		if($filename != "." && $filename != "..") {
			if(is_dir($dir."/".$filename)) {
				$arr['filename'] = $filename;
				$arr['path'] = substr($dir, 6);
				$file = new file();
				$file->set($arr);
				$file->setIs_dir(1);
				$db->insert('file', $file);
				store($dir."/".$filename, $arr, $db);
			} else {
				$arr['filename'] = $filename;
				$arr['path'] = substr($dir, 6);
				$file = new file();
				$file->set($arr);
				$db->insert("file", $file);
			}
		}
	}
	closedir($hander);
}
?>