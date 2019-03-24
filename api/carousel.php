<?php
/*从目录中读取轮播图片名称并与超链接进行配对*/

$dir = "img/carousel/";		// 图片地址关于index.html的相对路径，所有的轮播图片均以此为根目录

$filename = "../img/carousel/href.conf";		// 图片和超链接地址

/**
 * data是一个二维数组，每一个元素都是一对图片和超链接
 * 例：data = [ [
 *			   'img' => 'smile.jpg',		// 这是图片地址
 *			   'href' => 'baidu.com'		// 这是超链接
 *			 ], [
 *			   'img' => 'cry.jpg',
 *			   'href' => 'wangyi.com'
 *			 ] ];
 */
$data = [];

$file = fopen($filename, "r");
while($content = fgets($file)) {
	$content = trim($content);		// 去除内容中首位的空格
	if($content[0] === '#'){
		// 这是注释行
		continue;
	} else {
		$content = deleteSpace($content);	// 去除内容中所有的空格
		// 这是有效行
		$kv = explode("=", $content);
		$arr = ['img' => $dir.$kv[0].".jpg", 'href' => $kv[1]];
		$data[] = $arr;
	}
}

echo json_encode($data);		// 返回json字符串

/**
 * 删除字符串中所有的空格
 * @param String $str
 * @return String
 */
function deleteSpace($str) {
	$str_arr = str_split($str);
	$arr = [];
	foreach ($str_arr as $value) {
		if($value === ' ') {
			continue;
		} else {
			$arr[] = $value;
		}
	}
	return implode("", $arr);
}
?>