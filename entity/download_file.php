<?php
/************ 下载文件实体类 **************/
include_once("entity.php");

/**
 *      字段         类型      主键     默认值    允许为空     描述
 * |    email   |  varchar  |  是   |    无   |    否     |  用户邮箱
 * |   filename |  varchar  |  是   |    无   |    否     |  文件名
 * |    path    |  varchar  |  是   |    无   |    否     |  文件路径
 * |    ismark  |    int    |  否   |    0    |    否     |  是否评价
 * |    time    |    int    |  否   |    无   |    否     |  最近下载时间
 */
class download_file implements entity {
	private $info;

	public function __construct (){}

	public function set($info) {
		$this->info = $info;
	}

	public function setEmail(String $email) {
		$this->info['email'] = $email;
	}

	public function setFilename(String $filename) {
		$this->info['filename'] = $filename;
	}

	public function setPath(String $path) {
		$this->info['path'] = $path;
	}

	public function setIsmark(int $ismark) {
		$this->info['ismark'] = $ismark;
	}

	public function setTime(int $time) {
		$this->info['time'] = $time;
	}

	public function getEmail() {
		return $this->info['email'];
	}

	public function getFilename() {
		return $this->info['filename'];
	}

	public function getPath() {
		return $this->info['path'];
	}

	public function getIsmark() {
		return $this->info['ismark'];
	}

	public function getTime() {
		return $this->info['time'];
	}

/****************** Entity接口方法 ********************/

	public function getAttribute($name) {
		if(array_key_exists($name, $this->info)) {
			// 存在该字段
			return $this->info[$name];
		} else {
			return NULL;
		}		
	}

	public function getAttributes() {
		return $this->info;
	}

	public function getPrimaryKey() {
		return ['email', 'filename', 'path'];
	}

	public function getOtherKey() {
		return ['ismark', 'time'];
	}
}
?>