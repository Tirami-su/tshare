<?php
/************* question表 ***************/
include_once("entity.php");

/**
 *    字段        类型    主键     默认值    允许为空      描述
 * |  email  | varchar |  是   |    无   |    否     | 提问者邮箱
 * |   qid   |   int   |  是   |    无   |    否     | 问题编号
 * | subjetc | varchar |  否   |    无   |    否     | 问题标题
 * |  time   |   int   |  否   |    无   |    否     | 提问时间
 * | content | varchar |  否   |    无   |    否     | 问题描述
 */
class question implements entity {
	private $info;

	public function __construct() {}

	public function set($info) {
		$this->info = $info;
	}

	public function setEmail(String $email) {
		$this->info['email'] = $email;
	}

	public function setQid(int $qid) {
		$this->info['qid'] = $qid;
	}

	public function setSubject(String $subject) {
		$this->info['subject'] = $subject;
	}

	public function setTime(int $time) {
		$this->info['time'] = $time;
	}

	public function setContent(String $content) {
		$this->info['content'] = $content;
	}

	public function getEmail() {
		return $this->info['email'];
	}

	public function getQid() {
		return $this->info['qid'];
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
		return ['email', 'qid'];
	}

	public function getOtherKey() {
		return ['subject', 'time', 'content'];
	}
}
?>