<?php
/*********** 文件预览 ***********/
include_once("../../lib/PDF.php");
include_once("../../lib/FileProcess.php");

$valid = ["docx", "doc", "ppt", "pptx", "pdf", "pdf"];

$objFile = $_POST['file'];

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
if(!file_exists($destFile)) {
	FileProcess::createFolder($destFile);
}

if(in_array($ext, $valid)) {
	if(FileProcess::getFolderSize("../../".$destFile) == 0) {
		// 如果没有预览文件，则需要新建预览文件
		if($ext == "pdf") {
			// 如果源文件就是pdf，则先进行拆分，再转换为png图片
			$tempFile = $destFile."/预览.pdf";
			PDF::PDFSplit($srcFile, $tempFile, 10);		// 拆成一个小的pdf进行预览
			PDF::Pdf2Png($tempFile, $destFile);			// 将小pdf转为png图片
			unlink("../../".$tempFile);		// 删除小的预览pdf	
		} else {
			// 源文件不是pdf，需要先转为pdf再拆分，然后转为png图片
			$pdfTemp = "repository/temp/".$filename.".pdf";		// 大pdf路径
			PDF::Word2Pdf($srcFile, $pdfTemp);			// 转成pdf

			$tempFile = $destFile."/预览.pdf";		// 小pdf路径
			PDF::PDFSplit($pdfTemp, $tempFile, 10);	// 拆成一个小的pdf进行预览

			PDF::Pdf2Png($tempFile, $destFile);		// 小pdf转为png图片
			unlink("../../" . $pdfTemp);			// 删除大pdf文件
			unlink("../../" . $tempFile);			// 删除小pdf文件
		}
		
	}
	echo json_encode(['code' => 1, 'msg' => '转换完成']);
} else {
	// 不能预览
	echo json_encode(['code' => 0, 'msg' => "文件格式不支持预览"]);
}
?>