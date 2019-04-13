<?php
/*********** 文件预览 ***********/
include_once("../../lib/PDF.php");

$valid = ["docx", "doc", "ppt", "pptx", "pdf", "pdf"];

$srcFile = "upload_file/".$_POST['file'];		// 待预览的文件路径（相对于网站根目录的路径）

$list = explode(".", $srcFile);
$ext = $list[1];
$filename = $list[0];

$tempFile = $filename . ".pdf";
$destFile = $filename . "-预览.pdf";

if(in_array($ext, $valid)) {
	if(!file_exists("../../".$destFile)) {
		// 如果没有预览文件，则需要新建预览文件
		if($ext == "pdf") {
			// 如果源文件就是pdf，则只需要进行拆分
			PDF::PDFSplit($srcFile, $destFile, 10);		// 拆成一个小的pdf进行预览
		} else {
			// 源文件不是pdf，需要先转为pdf再拆分
			PDF::Word2Pdf($srcFile, $tempFile);			// 转成pdf
			PDF::PDFSplit($tempFile, $destFile, 10);	// 拆成一个小的pdf进行预览
			unlink("../../" . $tempFile);				// 删除完整的pdf文件
		}
		
	}
	echo json_encode(['code' => 1, 'msg' => $destFile]);
} else {
	// 不能预览
	echo json_encode(['code' => 0, 'msg' => "文件格式不支持预览"]);
}
?>