<?php
/**************** cookie类 *******************/

include_once(dirname(__FILE__)."/cryption/cryption.php");
include_once(dirname(__FILE__)."/cryption/conversion.php");

class cookie {
	/**
	 * 设置cookie
	 * @param String $name   cookie名（key）
	 * @param String $value  cookie值（value）
	 * @param int    $expire cookie过期时间
	 * @param String $path   cookie有效路径
	 * @param String $domain cookie有效域名
	 * @return String 返回加密密钥和解密密钥
	 */
	public static function set(String $name, String $value='', String $encrypt_key='', int $expire=0, String $path="", String $domain="") {
		// 首先对cookie值进行加密
		$encode = new cryption();
		if($encrypt_key === '') {		// 如果没有传入加密密钥
			$encrypt_key = $encode->getEncrypt();		// 获取一个随即密钥
		}

		$list = $encode->encode($value, $encrypt_key);
		$value = $list['target_str'];
		$target_key = $list['target_key'];

		setcookie($name, $value, $expire, $path, $domain);
		return ['target_key' => $target_key, 'origin_key' => $encrypt_key];
	}

	/**
	 * 获取cookie值
	 * @param String $name cookie名
	 * @param String $decrypt_key 解密密钥
	 */
	public static function get($name, $decrypt_key) {
		if(isset($_COOKIE[$name])) {
			// cookie存在
			$decode = new cryption();
			$value = $_COOKIE[$name];
			$value = $decode->decode($value, $decrypt_key);
			return $value;
		}
	}

	/**
	 * 销毁cookie
	 * @param String $name cookie名
	 */
	public static function destroy($name) {
		setcookie($name, "", time()-3600);
	}
}
?>