<?php
/*********** 文件预览 ***********/
/**
 * 接收参数，如果是可以预览的文件，则转化为一个指定页数的pdf
 */

include_once("../../lib/PDF.php");
include_once("../../lib/FileProcess.php");
include_once("../../lib/Image.php");

$valid = ["docx", "doc", "ppt", "pptx", "pdf", "pdf"];

$objFile = $_GET['url'];

$list = explode(".", $objFile);
$ext = $list[count($list)-1];
$filename = "";
for($i=0;$i<count($list)-1;$i++) {
	$filename .= ($list[$i].".");
}
$filename = substr($filename, 0, strlen($filename)-1);

$srcFile = "upload_file/".$objFile;				// 待预览的文件路径（相对于网站根目录的路径）
if(!file_exists("../../".$srcFile)) {
	echo json_encode(['code' => 0, 'msg' => '源文件不存在']);
	exit;
}

$destFile = "repository/temp/".$filename."-预览.pdf";	// 预览文件的保存路径（相对于网站根目录的路径）
$parentFile = dirname(dirname(__FILE__)."/../../".$destFile);		// 预览文件的父文件夹
if(!file_exists($parentFile)) {
	// 逐级创建父目录
	FileProcess::createFolder($parentFile);
}

if(in_array($ext, $valid)) {
	if(!file_exists("../temp/".$objFile.".svg")) {
		// 如果没有预览文件，则需要新建预览文件
		if($ext == "pdf") {
			// 如果源文件就是pdf，则只需要进行拆分
			PDF::PDFSplit($srcFile, $destFile, 10);	// 拆成一个小的pdf进行预览
		} else {
			// 源文件不是pdf，需要先转为pdf再拆分，然后转为png图片
			$pdfTemp = "repository/temp/".$filename.".pdf";		// 大pdf路径
			PDF::Word2Pdf($srcFile, $pdfTemp);			// 转成pdf
			PDF::PDFSplit($pdfTemp, $destFile, 10);	// 拆成一个小的pdf进行预览
			unlink("../../" . $pdfTemp);			// 删除大pdf文件
		}
	}
	echo json_encode(['code' => 1, 'msg' => '预览PDF生成成功']);
} else {
	// 不能预览
	echo json_encode(['code' => 0, 'msg' => "文件格式不支持预览"]);
}
?>