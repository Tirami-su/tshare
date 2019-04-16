<?php
/*********** 文件预览 ***********/
/**
 * 接收参数，如果是可以预览的文件，则转化为一个一张长图
 */

include_once("../../lib/PDF.php");
include_once("../../lib/FileProcess.php");
include_once("../../lib/Image.php");
include_once("../../lib/Db.php");
include_once("../../entity/FileHeight.php");
$db = new Db();

$valid = ["docx", "doc", "ppt", "pptx", "pdf"];

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

$destFile = "repository/temp/".$objFile;	// 预览文件的保存路径（相对于网站根目录的路径）

if(in_array($ext, $valid)) {
	$fh = $db->select("FileHeight", ['url' => $objFile]);
	if($fh === NULL) {
		// 如果没有预览文件，则需要新建预览文件
		if(!file_exists("../../".$destFile)) {
			// 逐级创建目录
			FileProcess::createFolder("../../".$destFile);
		}
		
		if($ext == "pdf") {
			// 如果源文件就是pdf，则只需要进行拆分
			$tempFile = $destFile."/预览.pdf";
			PDF::PDFSplit($srcFile, $tempFile, 10);	// 拆成一个小的pdf
			PDF::PDF2PNG($tempFile, $destFile);		// 将小pdf转为多个png图片
			unlink("../../".$tempFile);		// 删除小的pdf
		} else {
			// 源文件不是pdf，需要先转为pdf再拆分，然后转为png图片
			$pdfTemp = "repository/temp/".$objFile.".pdf";		// 大pdf路径
			PDF::Word2Pdf($srcFile, $pdfTemp);			// 转成pdf
			$tempFile = $destFile."/预览.pdf";
			PDF::PDFSplit($pdfTemp, $tempFile, 10);	// 拆成一个小的pdf进行预览
			unlink("../../" . $pdfTemp);			// 删除大pdf文件
			PDF::PDF2PNG($tempFile, $destFile);		// 将小pdf转为png图片
			unlink("../../".$tempFile);
		}

		// 将多张png图片合并
		$height = merge("../../".$destFile);
		$fh = new FileHeight();
		$fh->setUrl($objFile);
		$fh->setHeight($height);
		$db->insert("FileHeight", $fh);

		FileProcess::delDirectory("../../".$destFile);
		sleep(5);
	}
	echo json_encode(['code' => 1, 'height' => $fh->getHeight()]);
} else {
	// 不能预览
	echo json_encode(['code' => 0, 'msg' => "文件格式暂时不支持预览"]);
}

function merge(String $dir) {
	$handle = opendir($dir);
	$arr = array();
	while(($filename = readdir($handle)) !== false) {
		if($filename != "." && $filename != "..") {
			$arr[] = $dir."/".$filename;
		}
	}
	closedir($handle);
	Image::merge($arr, $dir.".png");
	$info = getimagesize($arr[0]);
	return $info[1];
}
?>