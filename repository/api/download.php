<?php
/*********** 下载文件 ***********
 * 如果要下载的是文件夹，打包成zip下载
 * 接收前台传来的参数:是文件的路径(父目录+文件名)
 * 下载完成后需要更新一下file数据表
 */

include_once("../../lib/zip.php");
include_once("../../lib/Db.php");
include_once("../../lib/FileProcess.php");
include_once("../../entity/file.php");

$url = "upload_file/".$_GET['url'];
$filename = $_GET['filename'];
$srcFile = "../../" . $url;		// 需要下载的文件的真正路径
$flag = false;

if(is_dir($srcFile)) {
	// 打包下载文件夹
	$zip = new zip();

	$list = explode("/", $url);
	$destFile = $srcFile . ".zip";
	$zip->compress_dir($srcFile, $destFile);
	$flag = FileProcess::download($destFile);
	// 下载完成后删除zip包
	unlink($destFile);
} else {
	// 直接下载文件
	$flag = FileProcess::download($srcFile);
}

if($flag !== true) {
	// 文件不存在，向前台返回一个结果
	echo json_encode($flag);
	exit;
}

// 如果文件存在并且下载成功，则需要更新数据库
$db = new Db();
// 拆分文件名和路径名
$list = FileProcess::splitPathAndFilename($url);
$file = $db->select("file", ['filename' => $filename, 'path' => $list['path']]);
if($file !== NULL) {
	$file->setDownload($file->getDownload() + 1);
	$db->update("file", $file);
}
?>