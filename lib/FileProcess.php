<?php
/********** 文件操作相关 *************/

class FileProcess {
	/**
	 * 文件单位转换
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
}

?>