<?php
/************** FileHeight数据表 ***************/
/*****     保存预览长图的每一张图片的高度     ****/
include_once("entity.php");

/**
 *    字段      类型      主键    默认值    允许为空     描述
 * |  url  |  varchar  |  是   |   无   |    否     |  文件url
 * | height|    int    |  否   |   0    |    否     |  一张图片的高度
 */
class FileHeight implements entity {
	private $info;

	public function __construct() {}

	public function set($info) {
		$this->info = $info;
	}

	public function setUrl(String $url) {
		$this->info['url'] = $url;
	}

	public function setHeight(int $height) {
		$this->info['height'] = $height;
	}

	public function getUrl() {
		return $this->info['url'];
	}

	public function getHeight() {
		return $this->info['height'];
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
		return ['url'];
	}

	public function getOtherKey() {
		return ['height'];
	}
}
?>