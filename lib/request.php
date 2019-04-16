<?php
/********** 使用php脚本向其他php页面发送请求 **************/

class request {
	/**
	 * 发送GET请求
	 * @param String $url 请求目标地址
	 * @param array $data 请求参数(一个关联数组key=>value)
	 * @param int $timeout 超时时间
	 */
	public static function sendGetRequest(String $url, $data, int $timeout = 5) {
		if($url == "" || $timeout < 0) {
			return false;
		}

		// 封装url
		$url .= ("?" . http_build_query($data));

		$ch = curl_init();		// 初始化连接句柄
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
 		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		$flag = curl_exec($ch);
 		curl_close($ch);
 		return $flag;
	}

	/**
	 * 发送POST请求
	 * @param String $url 请求目标地址
	 * @param array $data 请求参数(一个关联数组key=>value)
	 * @param int $timeout 超时时间
	 */
	public static function sendPostRequest(String $url, $data, int $timeout = 5) {
		if($url == "" || $data == "" || $timeout < 0) {
			return false;
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		$flag = curl_exec($ch);
 		curl_close($ch);
 		return $flag;
	}
}
?>