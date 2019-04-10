<?php

class PDFConverter {
	private static $root = "C:\\\\Users\\\\fuhao\\\\Desktop\\\\software_engineering\\\\tshare\\\\";
	/**
	 * 将word(*.doc, *.docx)转为pdf
	 * @param $src 原文件相对于网站根目录的完整路径(所有路径均按php规则书写/)
	 * @param $dest 目标文件相对于网站根目录的完整路径
	 */
	public static function Word2Pdf(String $src, String $dest) {
		require_once("java/Java.inc");
		$PDFConverter = new Java("fuhao.PDFConverter");
		// 将所有的"/"替换位"\\"
		$src = str_replace("/", "\\\\", $src);
		$dest = str_replace("/", "\\\\", $dest);

		$PDFConverter->Word2Pdf(self::$root.$src, self::$root.$dest);
	}
}
?>