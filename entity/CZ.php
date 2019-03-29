<?php
/************** 层主回答表 *****************/

include_once("entity.php");

/**
 *     字段         类型      主键     默认值    允许为空      描述
 * |    aid    |    int    |  是   |    无   |    否     | 答案编号
 * |    pid    |    int    |  是   |    无   |    否     | 问题编号
 * |   puid    |    int    |  是   |    无   |    否     | 提问者学号
 * |   auid    |    int    |  否   |    无   |    否     | 回答者学号
 * |   time    |    int    |  否   |    无   |    否     | 回答时间
 * |   agree   |    int    |  否   |    0    |    否     | 答案赞同人数
 * | disagree  |    int    |  否   |    0    |    否     | 答案否定人数
 * |  content  |  varchar  |  否   |    无   |    否     | 答案内容
 */
class CZ implements entity {
	private $info;

	public function __construct() {}

	public function set($info) {
		$this->info = $info;
	}

	public function setAid(int $aid) {
		$this->info['aid'] = $aid;
	}

	public function setPid(int $pid) {
		$this->info['pid'] = $pid;
	}

	public function setPuid(int $puid) {
		$this->info['puid'] = $puid;
	}

	public function setAuid(int $auid) {
		$this->info['auid'] = $auid;
	}

	public function setTime(int $time) {
		$this->info['time'] = $time;
	}

	public function setAgree(int $agree) {
		$this->info['agree'] = $agree;
	}

	public function setDisagree(int $disagree) {
		$this->info['disagree'] = $disagree;
	}

	public function setContent(String $content) {
		$this->info['content'] = $content;
	}

	public function getAid() {
		return $this->info['aid'];
	}

	public function getPid() {
		return $this->info['pid'];
	}

	public function getPuid() {
		return $this->info['puid'];
	}

	public function getAuid() {
		return $this->info['auid'];
	}

	public function getTime() {
		return $this->info['time'];
	}

	public function getAgree() {
		return $this->info['agree'];
	}

	public function getDisagree() {
		return $this->info['disagree'];
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
		return ['aid', 'pid', 'uid'];
	}

	public function getOtherKey() {
		return ['auid', 'time', 'agree', 'disagree', 'content'];
	}
}

?>