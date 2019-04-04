<?php
/************* 通知暂存表 ***************/
include_once("entity.php");

/**
 *    字段         类型      主键     默认值    允许为空      描述
 * |   nid    |    int    |  是   |    无   |    否     |  通知编号
 * | address  |  varchar  |  是   |    无   |    否     |  接收者学号
 */
class noticeTemp implements entity {

	private $info;

	public function __construct() {}

	public function set($info) {
		$this->info = $info;
	}

	public function setNid(int $nid) {
		$this->info['nid'] = $nid;
	}

	public function setAddress(String $address) {
		$this->info['address'] = $address;
	}

	public function getNid() {
		return $this->info['nid'];
	}

	public function getAddress() {
		return $this->info['address'];
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
		return ['nid', 'address'];
	}

	public function getOtherKey() {
		return [];
	}
}
?>