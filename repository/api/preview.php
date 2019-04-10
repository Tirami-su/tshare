<?php
/*********** 文件预览 ***********/
include_once("../../lib/PDFConverter.php");

$valid = ["docx", "doc", "ppt", "pptx", "pdf"];

$srcFile = "体系结构.ppt";		// 待预览的文件路径（相对于网站根目录的路径）

$list = explode(".", $srcFile);
$ext = $list[1];
$filename = $list[0];

$destFile = $filename . ".pdf";

if(in_array($ext, $valid)) {
	// 可以预览
	if($ext == "pdf") {
		// 直接预览
		echo json_encode(['code' => 1]);
	} else {
		// 转成pdf在预览
		PDFConverter::Word2Pdf($srcFile, $destFile);
		echo json_encode(['code' => 1, 'msg' => $destFile]);
	}
} else {
	// 不能预览
	echo json_encode(['code' => 0, 'msg' => "文件格式不支持预览"]);
}
?>