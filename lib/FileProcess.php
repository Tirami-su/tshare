<?php
/********** 文件操作相关 *************/

class FileProcess {
	/**
	 * 文件单位转换
	 * @param int $size 字节数(单位为B)
	 * @return String 转换为合适的单位
	 */
	public static function getSize($size) {
		$time = 0;
		$unit = "b";
		while($size > 1000) {
			$time++;
			$size /= 1024;
		}

		if($time === 1) {
			$unit = "KB";
		} else if($time === 2) {
			$unit = "MB";
		} else if($time === 3) {
			$unit = "GB";
		}

	    return number_format($size, $time-1).$unit;
	}

	/**
	 * 获取文件夹的大小
	 * @param String $dir 文件夹路径
	 * @return 返回文件夹的字节数
	 */
	public static function getFolderSize($dir) {
		$count_size = 0;
		$dir_array = scandir($dir);
		foreach ($dir_array as $key => $filename) {
			if($filename != "." && $filename != "..") {
				if(is_dir($dir . "/" . $filename)) {
					$new_foldersize = self::getFolderSize($dir . "/" . $filename);
					$count_size += $new_foldersize;
				} else {
					$count_size += filesize($dir . "/" . $filename);
				}
			}
		}
		return $count_size;
	}

	/**
	 * 通过文件的完整路径拆分文件的父目录和文件名
	 * @param String $url 文件的完整路径
	 * @return array
	 * @example 输入：upload_file/2019/计算机/zip/160400423@stu.hit.edu.cn_操作系统/2005春期末考试.docx
	 *			输出：['filename' => '2005春期末考试.docx', 'path' => 'upload_file/2019/计算机/zip/160400423@stu.hit.edu.cn_操作系统']
	 */
	public static function splitPathAndFilename($url) {
		$list = explode("/", $url);
		$filename = $list[count($list)-1];
		$path = substr($url, 0, strlen($url)-strlen($filename)-1);
		return ['filename' => $filename, 'path' => $path];
	}

	/**
	 * 根据文件路径下载文件
	 * @param String $url 文件路径
	 * @return 如果文件不存在,返回['code' => 0, 'msg' => '文件不存在']
	 */
	public static function download(String $url) {
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

	/**
	 * 逐级创建文件夹
	 * @param String $dir 待创建的文件夹路径(相对于网站根目录的路径，路径按php规则编写，分隔符:"/")
	 */
	public static function createFolder(String $dir, int $pro) {
		$path = "";
		$list = explode("/", $dir);
		for($i=0;$i<count($list);$i++) {
			if(!file_exists($path.$list[$i])) {
				mkdir($path.$list[$i], $pro);
				chmod($path.$list[$i], $pro);
			}
			$path .= $list[$i]."/";
		}
	}

	/**
	 * 删除文件夹
	 * @param String $dir
	 */
	public static function delDirectory(String $dir) {
		$handle = opendir($dir);
		while(($filename = readdir($handle)) !== false) {
			if($filename != "." & $filename != "..") {
				if(is_dir($dir."/".$filename)) {
					self::delDirectory($dir."/".$filename);
				} else {
					unlink($dir."/".$filename);
				}
			}
		}
		closedir($handle);
		rmdir($dir);
	}
}

?>