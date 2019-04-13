<?php
/********** PDF处理类 ***********/
class PDF {
	/**
	 * 将word(*.doc, *.docx, *ppt, *.pptx, *.excel)转为pdf
	 * @param $src 原文件相对于网站根目录的完整路径(所有路径均按php规则书写，分隔符："/")
	 * @param $dest 目标文件相对于网站根目录的完整路径
	 */
	public static function Word2Pdf(String $src, String $dest) {
		require_once("java/Java.inc");
		$root = dirname(__FILE__);
		$root = str_replace("\\", "\\\\", $root);

		$PDFConverter = new Java("fuhao.PDFConverter");
		// 将所有的"/"替换位"\\"
		$src = str_replace("/", "\\\\", $src);
		$dest = str_replace("/", "\\\\", $dest);
		$root .= "\\\\..\\\\";

		$PDFConverter->Word2Pdf((String)$root.$src, (String)$root.$dest);
	}

	/**
	 * 拆分pdf，如果pdf的总页数不够拆分，则按总页数进行拆分
	 * @param String $src 源文件相对于网站根目录的完整路径(按php规则编写路径，分隔符："/")
	 * @param String $dest 目标文件相对于网站根目录的完整路径
	 * @param int pages 拆分页数
	 */
	public static function PDFSplit(String $src, String $dest, int $pages) {
		$root = dirname(__FILE__) . "\..\\";

		$pypath = dirname(__FILE__) . "/python/pdf.py";
		$src = $root.$src;
		$dest = $root.$dest;

		$cmd = $pypath . " " . $src . " " . $dest . " " . $pages;
		shell_exec($cmd);
	}
}
?>