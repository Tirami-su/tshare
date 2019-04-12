<?php
/**
 * 在文件名末尾添加最后修改的时间戳
 * 参数必须是从网站根目录开始的绝对路径
 */
function auto_version($file) {
	// 如果找不到这个文件，原样返回文件名
	if (strpos($file, '/') !== 0 || !file_exists($_SERVER['DOCUMENT_ROOT'].$file))
		return $file;
	// 获取文件的最后修改时间，添加到文件名的末尾 
	$mtime = filemtime($_SERVER['DOCUMENT_ROOT'].$file);
	return preg_replace('{\\.([^./]+)$}', ".$mtime.\$1", $file);
}
?>