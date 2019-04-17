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
		$root .= "\\..\\";
		$root = str_replace("\\", DIRECTORY_SEPARATOR, $root);

		$PDFConverter = new Java("fuhao.PDFConverter");
		// 将所有的"/"替换位"\\"
		$src = str_replace("/", DIRECTORY_SEPARATOR, $src);
		$dest = str_replace("/", DIRECTORY_SEPARATOR, $dest);

		// $PDFConverter->Word2Pdf((String)dirname(__FILE__)."/java/soffice.sh" ,(String)$root.$src, (String)$root.$dest);
		$PDFConverter->Word2Pdf((String)$root.$src, (String)$root.$dest);
	}

	/**
	 * 拆分pdf，如果pdf的总页数不够拆分，则按总页数进行拆分
	 * @param String $src 源文件相对于网站根目录的完整路径(按php规则编写路径，分隔符："/")
	 * @param String $dest 目标文件相对于网站根目录的完整路径
	 * @param int pages 拆分页数
	 */
	public static function PDFSplit(String $src, String $dest, int $pages) {
		require_once 'java/Java.inc';
		$root = dirname(__FILE__);
		$root .= "\\..\\";
		$root = str_replace("\\", DIRECTORY_SEPARATOR, $root);

		$PDFProcess = new Java("fuhao.PDFProcess");
		// 将所有的"/"替换位"\\"
		$src = str_replace("/", DIRECTORY_SEPARATOR, $src);
		$dest = str_replace("/", DIRECTORY_SEPARATOR, $dest);

		$PDFProcess->split((String)$root.$src, (String)$root.$dest, (int)$pages);
	}

	/**
	 * pdf转png图片
	 * @param String $src pdf路径（相对于网站根目录，采用php规则书写路径，分隔符:"/"）
	 * @param String $dest png图片父目录（相对于网站根目录，采用php规则书写路径，分隔符:"/"）
	 */
	public static function PDF2PNG(String $src, String $dest) {
		require_once 'java/Java.inc';
		$root = dirname(__FILE__);
		$root .= "\\..\\";
		$root = str_replace("\\", DIRECTORY_SEPARATOR, $root);

		$PDFProcess = new Java("fuhao.PDFProcess");
		// 将所有的"/"替换位"\\"
		$src = str_replace("/", DIRECTORY_SEPARATOR, $src);
		$dest = str_replace("/", DIRECTORY_SEPARATOR, $dest);

		$PDFProcess->Pdf2Png((String)$root.$src, (String)$root.$dest);
	}
}
?>