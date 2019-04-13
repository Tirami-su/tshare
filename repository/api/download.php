<?php
/*********** 下载文件 ***********/
/**
 * 如果要下载的是文件夹，打包成zip下载
 */
include_once("../../lib/zip.php");

$srcFile = "../../upload_file/".$_POST['file'];		// 需要下载的文件的真正路径
$flag = false;

if(is_dir($srcFile)) {
	// 打包下载文件夹
	$zip = new zip();
	$destFile = $srcFile . ".zip";
	$zip->compress_dir($srcFile, $destFile);
	$flag = download($destFile);
	unlink($destFile);
} else {
	// 直接下载文件
	$flag = download($srcFile);
}

if($flag !== true) {
	return json_encode($flag);
}

function download(String $url) {
	$list = explode("/", $url);
	$filename = $list[count($list)-1];
	if(!file_exists($url)) {
		return ['code' => 0, 'msg' => '文件不存在'];
	} else {
		$filesize = filesize($url);
		Header("Content-type: application/octet-stream");
		Header("Accept-Ranges: bytes");
		Header("Accept-Length: " . $filesize);
		Header("Content-Disposition: attachment; filename=" . $filename);

		$file = fopen($url, "r");
		$buf = 1024;
		$size = 0;
		while(!feof($file) && ($filesize - $size) > 0) {
			$data = fread($file, $buf);
			$size += $buf;
			echo $data;
		}
		fclose($file);
	}
	return true;
}
?>