<?php
/*************** 字符串与16进制数转换 *******************/

/**
 * 字符串转16进制数
 * @param String $str 目标字符串
 * @return String 16进制数组成的字符串
 */
function String2Hex(String $str) {
	return bin2hex($str);
}

/**
 * 16进制数转字符串
 * @param String $hex 16进制数组成的字符串
 * @return String 转换后的字符串
 */
function Hex2String(String $hex) {
	// 16进制数每两位表示一个字节
	$str = "";
	for($i=0;$i<strlen($hex);$i+=2) {
		$str .= chr(hexdec($hex[$i].$hex[$i+1]));
	}
	return $str;
}

?>