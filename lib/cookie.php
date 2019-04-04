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
	 * @param bool   $flag   是否对cookie值进行加密
	 * @param String $encrypt_key 加密密钥
	 * @param String $path   cookie有效路径
	 * @param String $domain cookie有效域名
	 * @return String 如果进行加密操作，返回加密密钥和解密密钥；没有进行加密操作不返回任何值
	 */
	public static function set(String $name, String $value='', int $expire=0, bool $flag=false, String $encrypt_key='', String $path="", String $domain="") {
		$target_key;
		if($flag === true) {
			// 首先对cookie值进行加密
			$encode = new cryption();
			if($encrypt_key === '') {		// 如果没有传入加密密钥
				$encrypt_key = $encode->getEncrypt();		// 获取一个随即密钥
			}

			$list = $encode->encode($value, $encrypt_key);
			$value = $list['target_str'];
			$target_key = $list['target_key'];
		}
		setcookie($name, $value, $expire, $path, $domain);
		if($flag === true) {
			return ['target_key' => $target_key, 'origin_key' => $encrypt_key];
		}
	}

	/**
	 * 获取cookie值
	 * @param String $name cookie名
	 * @param String $decrypt_key 解密密钥
	 * @return cookie存在则返回正常值，不存在返回空
	 */
	public static function get($name, $decrypt_key="") {
		if(isset($_COOKIE[$name])) {
			$value = $_COOKIE[$name];
			if($decrypt_key === "") {
				// 不需要解密
				return $value;
			} else {
				// 需要解密
				$decode = new cryption();
				$value = $decode->decode($value, $decrypt_key);
				return $value;
			}
		}
		return NULL;
	}

	/**
	 * 销毁cookie
	 * @param String $name cookie名
	 */
	public static function destroy($name) {
		setcookie($name, "", time()-3600, "/");
	}
}
?>