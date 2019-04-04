<?php
/************* ASE加密 ********************/
include_once("conversion.php");

class cryption {
	private $sbox;			// s盒
	private $inverse_sbox;  // 逆s盒

	public function __construct(){
		$this->init();
	}

	/**
	 * 加密
	 * @param String $str 待加密的字符串
	 * @param String $encrypt_key 初始密钥
	 * @return 关联数组['str', 'key']
	 */
	public function encode($str, $encrypt_key) {
		// 将字符串长度填充到16的倍数
		$len = strlen($str);
		if($len % 16 <> 0) {
			$differ = 16-strlen($str)%16;
			for($i=$len+1;$i<$len+$differ;$i++) {
				$str[$i] = dechex(mt_rand(0, 16));
			}
			$str[$len] = "$";
		}

		$origin_key = strtoupper($encrypt_key);	// 初始密钥

		// 保存加密后的16进制串
		$target_str = "";
		// 保存迭代后的密钥
		$target_key = "";

		// 每组16个字节进行加密
		for($i=0;$i<strlen($str);$i+=16) {
			$hex_key = $origin_key;

			$hex = substr($str, $i, 16);		// 取出16个字节

			// 转换位16进制串
			$hex = strtoupper(String2Hex($hex));

			// 十次迭代
			for($j=0;$j<10;$j++) {
				// 每一次迭代都将目标字符串与密钥进行异或运算
				$hex = $this->XOR($hex, $hex_key);

				// 字节代换（以s盒为标准）
				for($k=0;$k<16;$k++) {
					// 加密字符串的代换
					$idx_row = $hex[$k*2];
					$idx_col = $hex[$k*2+1];
					$value = $this->sbox[$idx_row][$idx_col];
					$hex[$k*2] = $value[0];
					$hex[$k*2+1] = $value[1];

					// 密钥的代换
					$idx_row = $hex_key[$k*2];
					$idx_col = $hex_key[$k*2+1];
					$value = $this->sbox[$idx_row][$idx_col];
					$hex_key[$k*2] = $value[0];
					$hex_key[$k*2+1] = $value[1];
				}
			}
			$target_str .= $hex;
			$target_key = $hex_key;
		}
		return ['target_str' => $target_str, 'target_key' => $target_key];
	}

	/**
	 * 解密
	 * @param String $str 已加密的16进制串
	 * @param String $decrypt_key 解密密钥
	 * @return String 解密后的字符串
	 */
	public function decode($str, $decrypt_key) {
		$target_str = "";		// 保存解密16进制串

		for($i=0;$i<strlen($str);$i+=32) {
			$hex = substr($str, $i, 32);		// 取出16个字节
			$hex_key = $decrypt_key;

			for($j=0;$j<10;$j++) {
				// 字节代换（以逆s盒位标准）
				for($k=0;$k<16;$k++) {
					// 加密字符串的代换
					$idx_row = $hex[$k*2];
					$idx_col = $hex[$k*2+1];
					$value = $this->inverse_sbox[$idx_row][$idx_col];
					$hex[$k*2] = $value[0];
					$hex[$k*2+1] = $value[1];

					// 密钥的代换
					$idx_row = $hex_key[$k*2];
					$idx_col = $hex_key[$k*2+1];
					$value = $this->inverse_sbox[$idx_row][$idx_col];
					$hex_key[$k*2] = $value[0];
					$hex_key[$k*2+1] = $value[1];
				}

				// 与密钥进行异或运算
				$hex = $this->XOR($hex, $hex_key);
			}
			$target_str .= $hex;
		}
		$str = Hex2String($target_str);
		$list = explode("$", $str);
		return $list[0];
	}

	/**
	 * 随机生成一个初始密钥
	 */
	public function getEncrypt() {
		$encrypt_key = "";
		for($i=0;$i<16;$i++) {
			$rand = mt_rand(0, 2);
			switch ($rand) {
				case 0:
					// 生成数字
					$encrypt_key .= dechex(mt_rand(48, 57));
					break;
				case 1:
					$encrypt_key .= dechex(mt_rand(65, 90));
					// 生成大写字母
					break;
				case 2:
					// 生成小写字母
					$encrypt_key .= dechex(mt_rand(97, 122));
					break;
			}
		}
		return $encrypt_key;
	}

	/**
	 * 两个长度相同的16进制字符串进行异或操作
	 * @param String $hex1
	 * @param String $hex2
	 * @return String 返回结果字符串（16进制）
	 */
	public function XOR($hex1, $hex2) {
		$res = "";
		for($j=0;$j<strlen($hex1);$j++) {
			$dest = intval(hexdec($hex1[$j])) ^ intval(hexdec($hex2[$j]));
			$dest = dechex($dest);
			$res .= $dest;
		}
		return strtoupper($res);
	}

	/**
	 * 初始化s盒和逆s盒
	 */
	private function init() {
		// 从文件中读取
		$filename = dirname(__FILE__)."/sbox.ini";
		$file = fopen($filename, "r");

		$flag = true;		// s盒和逆s盒读取标志

		while($content = fgets($file)) {
			$content = trim($content);		// 去除内容中首位的空格
			if($content === "") {
				// 空行
				continue;
			}
			if($content[0] === '#'){
				// 这是注释行
				continue;
			}

			// 这是有效行
			$kv = explode(" ", $content);
			$index = $kv[0];
			if($flag) {
				// 为true时读取s盒
				for($i=1;$i<17;$i++){
					$this->sbox[$index][strtoupper(dechex($i-1))] = $kv[$i];
				}
				
				if($index == "F") {
					$flag = false;
				}
			} else {
				// 为false的时候读取逆s盒
				for($i=1;$i<17;$i++){
					$this->inverse_sbox[$index][strtoupper(dechex($i-1))] = $kv[$i];
				}
			}
		}
	}
}

?>