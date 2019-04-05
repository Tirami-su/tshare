<?php
/************ answer表 **************/
include_once("entity.php");

/**
 *      字段       类型     主键    默认值    允许为空      描述
 * | que_email | varchar |  是   |   无   |    否    | 提问者邮箱
 * |    qid    |   int   |  是   |   无   |    否    | 问题编号
 * |    aid    |   int   |  是   |   无   |    否    | 答案编号
 * | ans_email | varchar |  否   |   无   |    否    | 回答者邮箱
 * |   time    |   int   |  否   |   无   |    否    | 回答时间
 * |  content  | varchar |  否   |   无   |    否    | 答案内容
 * |   agree   |   int   |  否   |   0    |    否    | 点赞量
 */
class answer implements entity {
	private $info;

	public function __construct() {}

	public function set($info) {
		$this->info = $info;
	}

	public function setQue_email(String $que_email) {
		$this->info['que_email'] = $que_email;
	}

	public function setQid(int $qid) {
		$this->info['qid'] = $qid;
	}

	public function setAid(int $aid) {
		$this->info['aid'] = $aid;
	}

	public function setAns_email(String $ans_email) {
		$this->info['ans_email'] = $ans_email;
	}

	public function setTime(int $time) {
		$this->info['time'] = $time;
	}

	public function setContent(String $content) {
		$this->info['content'] = $content;
	}

	public function setAgree(int $agree) {
		$this->info['agree'] = $agree;
	}

	public function getQue_email() {
		return $this->info['que_email'];
	}

	public function getQid() {
		return $this->info['qid'];
	}

	public function getAid() {
		return $this->info['aid'];
	}

	public function getAns_email() {
		return $this->info['ans_email'];
	}

	public function getTime() {
		return $this->info['time'];
	}

	public function getContent() {
		return $this->info['content'];
	}

	public function getAgree() {
		return $this->info['agree'];
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
		return ['que_email', 'qid', 'aid'];
	}

	public function getOtherKey() {
		return ['ans_email', 'time', 'content', 'agree'];
	}
}
?>