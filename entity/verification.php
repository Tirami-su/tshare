<?php
/**************** 验证码实体类 *********************/
include_once("entity.php");

/**
 *对应于数据库中的verification实体表
 *    字段       类型       主键     默认值    允许为空      描述
 * |   id    |   int     |  是   |    无   |    否     |   学号
 * |  code   |  varchar  |  否   |    无   |    否     |   验证码
 * |  time   |   int     |  否   |    无   |    否     |   到期时间
 */
class verification implements entity {

	/* 验证码信息（学号、验证码、到期时间） */
	private $info;			// 关联数组类型，数组的键对应于数据库表中的字段名称

	public function __construct() {}

	public function set($info) {
		$this->info = $info;
	}

	public function setId(int $id) {
		$this->info['id'] = $id;
	}

	public function setCode(String $code) {
		$this->info['code'] = $code;
	}

	public function setTime(int $time) {
		$this->info['time'] = $time;
	}

	public function getId() {
		return $this->info['id'];
	}

	public function getCode() {
		return $this->info['code'];
	}

	public function getTime() {
		return $this->info['time'];
	}


/****************** entity接口方法 ********************/

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
		return ['id'];
	}

	public function getOtherKey() {
		return ['code', 'time'];
	}
}

?>