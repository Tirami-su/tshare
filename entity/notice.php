<?php
/************* 通知表 **************/
include_once("entity.php");

/**
 *     字段         类型      主键    默认值   允许为空    描述
 * |    nid    |    int    |  是   |   无   |   否   | 消息编号
 * |   sender  |  varchar  |  否   |   无   |   是   | 发件人学号（为空表示由管理员发送）
 * |  address  |  varchar  |  否   |   无   |   是   | 接收者学号（为空表示向所有用户发送）
 * |  content  |  varchar  |  否   |   无   |   否   | 通知内容
 * |   time    |    int    |  否   |   无   |   否   | 通知时间
 * |  received |    int    |  否   |   0    |   否   |该消息是否被接收(0表示没有) 
 */
class notice implements entity {
	private  $info;

	public function __construct() {}

	public function set($info) {
		$this->info = $info;
	}

	public function setNid(int $nid) {
		$this->info['nid'] = $nid;
	}

	public function setSender($sender) {
		$this->info['sender'] = $sender;
	}

	public function setAddress($address) {
		$this->info['address'] = $address;
	}

	public function setContent(String $content) {
		$this->info['content'] = $content;
	}

	public function setTime(int $time) {
		$this->info['time'] = $time;
	}

	public function setReceived(int $received) {
		$this->info['received'] = $received;
	}

	public function getNid() {
		return $this->info['nid'];
	}

	public function getSender() {
		return $this->info['sender'];
	}

	public function getAddress() {
		return $this->info['address'];
	}

	public function getContent() {
		return $this->info['content'];
	}

	public function getTime() {
		return $this->info['time'];
	}

	public function getReceived() {
		return $this->info['received'];
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
		return ['nid'];
	}

	public function getOtherKey() {
		return ['sender', 'address', 'content', 'time', 'received'];
	}
}

?>