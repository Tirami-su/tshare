<?php
/********** 个人信箱表 **************/
include_once("entity.php");

/**
 *     字段         类型      主键    默认值   允许为空      描述
 * |    mid    |    int    |  是   |   无   |   否   | 信箱编号(接收者学号)
 * |   last    |    int    |  否   |   0    |   否   | 上一次接收的消息编号
 * |  update   |    int    |  否   |   0    |   否   | 上一次接收消息后是否有新消息
 * |    num    |    int    |  否   |   0    |   否   | 上一次接收消息后新消息的数量 
 */
class mailbox implements entity {
	private $info;

	public function __construct() {}

	public function set($info) {
		$this->info = $info;
	}

	public function setMid(int $mid) {
		$this->info['mid'] = $mid;
	}

	public function setLast(int $last) {
		$this->info['last'] = $last;
	}

	public function setUpdate(int $update) {
		$this->info['update'] = $update;
	}

	public function setNum(int $num) {
		$this->info['num'] = $num;
	}

	public function getMid() {
		return $this->info['mid'];
	}

	public function getLast() {
		return $this->info['last'];
	}

	public function getUpdate() {
		return $this->info['update'];
	}

	public function getNum() {
		return $this->info['num'];
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
		return ['mid'];
	}

	public function getOtherKey() {
		return ['last', 'update', 'num'];
	}
}
}
?>