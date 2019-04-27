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
include_once("../../entity/download_file.php");
include_once("../../entity/user.php");
session_start();

$url = "upload_file/".$_POST['url'];
$filename = $_POST['filename'];
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
	echo json_encode(['code' => 0, 'msg' => '文件不存在']);
	exit;
}

// 如果文件存在并且下载成功，则需要更新数据库
$db = new Db();

// 建立一条下载记录
$user = $_SESSION['user'];
$dlFile = $db->select("download_file", ['email' => $uesr->getEmail(), 'filename' => $filename, 'path' => $list['path']]);
if($dlFile === NULL) {
	// 只有第一次下载资料才能对下载量造成影响

	// 之前没有这样的记录，那么创建新的下载记录
	$dlFile = new download_file();
	$dlFile->setEmail($user->getEmail());
	$dlFile->setFilename($filename);
	$dlFile->setPath($list['path']);
	$dlFile->setTime(time());
	$db->insert("download_file", $dlFile);

	// 拆分文件名和路径名
	$list = FileProcess::splitPathAndFilename($url);
	$file = $db->select("file", ['filename' => $filename, 'path' => $list['path']]);
	if($file !== NULL) {
		$file->setDownload($file->getDownload() + 1);
		$db->update("file", $file);
	}
} else {
	// 以前下载过这个文件，更新最近下载时间，并要求重新评价
	$dlFile->setTime(time());
	$dlFile->setIsmark(0);
	$db->update("download_file", $dlFile);
}

echo json_encode(['code' => 1, 'msg' => '下载成功，请稍后对资料文件进行评价']);
?>