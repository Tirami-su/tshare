<?php
/************* 图片处理 ****************/

class Image {
	/**
	 * 竖直方向上合并图片(合并为png格式)，目标图片的宽度取所有图片中宽度最大的那张，高度取所有图片高度之和
	 * @param array 一组图片的路径
	 * @param String $dest 合并之后的图片地址
	 */
	public static function merge($source, String $dest) {
		ini_set("memory_limit", "500M");
		$width = 0;		// 合并后图片的宽
		$height = 0;	// 合并后图片的高

		$arr = array();
		for($i=0;$i<count($source);$i++) {
			$info = getimagesize($source[$i]);		// 获取图片详细信息（宽、高、类型等）
			// 获取图片类型
			$type = image_type_to_extension($info[2], false);
			$fun = "Imagecreatefrom{$type}";		// 根据图片类型构造一个符合该类型图片的读取函数
			$arr[$i]['source'] = $fun($source[$i]);
			$arr[$i]['size'] = $info;

			if($arr[$i]['size'][0] > $width) {
				$width = $arr[$i]['size'][0];
			}
			$height += $arr[$i]['size'][1];
		}

		$merge = imagecreate($width, $height);

		$dst_x = 0;
		$dst_y = 0;
		for($i=0;$i<count($source);$i++) {
			imagecopy($merge, $arr[$i]['source'], $dst_x, $dst_y, 0, 0, $arr[$i]['size'][0], $arr[$i]['size'][0]);
			$dst_y += $arr[$i]['size'][1];
		}

		imagepng($merge, $dest);
		imagedestroy($merge);
	}
}
?>