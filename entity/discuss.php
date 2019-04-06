<?php
/************ discuss表 **************/
include_once("entity.php");

/**
 *      字段       类型     主键    默认值    允许为空      描述
 * | que_email | varchar |  是   |   无   |    否    | 提问者邮箱
 * |  que_id   |   int   |  是   |   无   |    否    | 问题编号
 * |  ans_id   |   int   |  是   |   无   |    否    | 答案编号
 * |  dis_id   |   int   |  是   |   无   |    否    | 讨论编号
 * | dis_email | varchar |  否   |   无   |    否    | 讨论者邮箱
 * |   time    |   int   |  否   |   无   |    否    | 讨论发表时间
 * |  content  | varchar |  否   |   无   |    否    | 讨论发表内容
 * |    to     |   int   |  否   |   0    |    否    | 对某条讨论的回复
 */
class discuss implements entity {
	private $info;

	public function __construct() {}

	public function set($info) {
		$this->info = $info;
	}

	public function setQue_email(String $que_email) {
		$this->info['que_email'] = $que_email;
	}

	public function setQue_id(int $que_id) {
		$this->info['que_id'] = $que_id;
	}

	public function setAns_id(int $ans_id) {
		$this->info['ans_id'] = $ans_id;
	}

	public function setDis_id(int $dis_id) {
		$this->info['dis_id'] = $dis_id;
	}

	public function setDis_email(String $dis_email) {
		$this->info['dis_email'] = $dis_email;
	}

	public function setTime(int $time) {
		$this->info['time'] = $time;
	}

	public function setContent(String $content) {
		$this->info['content'] = $content;
	}

	public function setTo(int $to) {
		$this->info['to'] = $to;
	}

	public function getQue_email() {
		return $this->info['que_email'];
	}

	public function getQue_id() {
		return $this->info['que_id'];
	}

	public function getAns_id() {
		return $this->info['ans_id'];
	}

	public function getDis_id() {
		return $this->info['dis_id'];
	}

	public function getDis_email() {
		return $this->info['dis_email'];
	}

	public function getTime() {
		return $this->info['time'];
	}

	public function getContent() {
		return $this->info['content'];
	}

	public function getTo() {
		return $this->info['to'];
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
		return ['que_email', 'que_id', 'ans_id', 'dis_id'];
	}

	public function getOtherKey() {
		return ['dis_email', 'time', 'content', 'to'];
	}
}
?>