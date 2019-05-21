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

// $url = $_GET['url'];
// $filename = $_GET['filename'];
$url = "2019/计算机/zip/{7F019EB6-41FD-0BA7-B17E-84F08679C231}";
$filename = "操作系统";

$list = FileProcess::splitPathAndFilename($url);		// 拆分文件名和路径名

$srcFile = "../../upload_file/".$url;
if(!file_exists($srcFile)) {
	// echo json_encode(['code' => 0, 'msg' => '文件不存在']);
	exit;
}

if(is_dir($srcFile)) {
	// 下载文件夹，需要打包成zip
	$zip = new zip();
	$destFile = "../../upload_file/".$list['path']."/".$filename.".zip";
	$zip->compress_dir($srcFile, $destFile);

	// 下载完后删除zip压缩包
	FileProcess::download($destFile);
	unlink($destFile);
} else {
	// 直接下载文件
	FileProcess::download($srcFile);
}

// 建立文件下载记录
$db = new Db();
$user = $_SESSION['user'];
$dlFile = $db->select("download_file", ['email' => $user->getEmail(), 'filename' => $filename, 'path' => $list['path']]);
$dlFile = NULL;
if($dlFile === NULL) {
	// 只有第一次下载资料才对下载量造成影响
	$path = $list['path'];
	if(substr($path, -3) === "zip") {
		$path .= "/";
	}
	$file = $db->select("file", ['filename' => $list['filename'], 'path' => $path]);
	if($file !== NULL) {
		$file->setDownload($file->getDownload() + 1);
		$db->update("file", $file);
	}

	// 建立新的下载项
	$dlFile = new download_file();
	$dlFile->setEmail($user->getEmail());
	$dlFile->setFilename($list['filename']);
	$dlFile->setPath($path);
	$dlFile->setTime(time());
	$db->insert("download_file", $dlFile);
} else {
	// 以前下载过这个文件，更新最近下载时间，并要求重新评价
	$dlFile->setTime(time());
	$dlFile->setIsmark(0);
	$db->update("download_file", $dlFile);
}