<?php
/*************** 楼主提问表 ******************/
include_once("entity.php");

/**
 *     字段         类型      主键     默认值    允许为空      描述
 * |    pid    |    int    |  是   |    无   |    否     | 问题编号
 * |    uid    |    int    |  是   |    无   |    否     | 提问者学号
 * |  subject  |  varchar  |  否   |    无   |    否     | 问题标题
 * |   time    |    int    |  否   |    无   |    否     | 提问时间
 * |  content  |  varchar  |  否   |    无   |    否     | 问题描述
 */
class LZ implements entity {
	private $info;

	public function __construct() {}

	public function set($info) {
		$this->info = $info;
	}

	public function setPid(int $pid) {
		$this->info['pid'] = $pid;
	}

	public function setUid(int $uid) {
		$this->info['uid'] = $uid;
	}

	public function setSubject(String $subject) {
		$this->info['subject'] = $subject;
	}

	public function setTime(int $time) {
		$this->info['time'] = $time;
	}

	public function setContent(int $content) {
		$this->info['content'] = $content;
	}

	public function getPid() {
		return $this->info['pid'];
	}

	public function getUid() {
		return $this->info['uid'];
	}

	public function getSubject() {
		return $this->info['subject'];
	}

	public function getTime() {
		return $this->info['time'];
	}

	public function getContent() {
		return $this->info['content'];
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
		return ['pid', 'uid'];
	}

	public function getOtherKey() {
		return ['subject', 'time', 'content'];
	}
}
?>