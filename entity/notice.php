<?php
/************* 通知表 **************/
include_once("entity.php");

/**
 *     字段         类型      主键    默认值   允许为空      描述
 * |    mid    |    int    |  是   |   无   |   否   | 信箱编号(接收者学号)
 * |    nid    |    int    |  是   |   无   |   否   | 消息编号
 * |   sender  |    int    |  否   |   无   |   是   | 发件人学号
 * |  content  |  varchar  |  否   |   无   |   否   |  通知内容
 * |   time    |    int    |  否   |   无   |   否   |  通知时间
 * |  receive  |    int    |  否   |   0    |   否   |  该消息是否被接收(0表示没有) 
 */
class notice implements entity {
	private  $info;

	public function __construct() {}

	public function set($info) {
		$this->info = $info;
	}

	public function setMid(int $mid) {
		$this->info['mid'] = $mid;
	}

	public function setNid(int $nid) {
		$this->info['nid'] = $nid;
	}

	public function setSender(int $sender) {
		$this->info['sender'] = $sender;
	}

	public function setContent(String $content) {
		$this->info['content'] = $content;
	}

	public function setTime(int $time) {
		$this->info['time'] = $time;
	}

	public function setReceive(int $receive) {
		$this->info['receive'] = $receive;
	}

	public function getMid() {
		return $this->info['mid'];
	}

	public function getNid() {
		return $this->info['nid'];
	}

	public function getSender() {
		return $this->info['sender'];
	}

	public function getContent() {
		return $this->info['content'];
	}

	public function getTime() {
		return $this->info['time'];
	}

	public function getReceive() {
		return $this->info['receive'];
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
		return ['mid', 'nid'];
	}

	public function getOtherKey() {
		return ['sender', 'content', 'time', 'receive'];
	}
}

?>