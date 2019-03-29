<?php
/************** 居民回答表 ******************/
include_once("entity.php");

/**
 *     字段         类型      主键     默认值    允许为空      描述
 * |   lz_id   |    int    |  是   |    无   |    否     | 问题编号
 * |  lz_uid   |    int    |  是   |    无   |    否     | 提问者学号
 * |   cz_id   |    int    |  是   |    无   |    否     | 答案编号
 * |    rid    |    int    |  是   |    无   |    否     | 讨论编号
 * |   ruid    |    int    |  否   |    无   |    否     | 讨论者学号
 * |   time    |    int    |  否   |    无   |    否     | 讨论发表时间
 * |  content  |  varchar  |  否   |    无   |    否     | 讨论发表内容
 */
class resident implements entity {
	private $info;

	public function __construct() {}

	public function set($info) {
		$this->info = $info;
	}

	public function setLz_id(int $lz_id) {
		$this->info['lz_id'] = $lz_id;
	}

	public function setLz_uid(int $lz_uid) {
		$this->info['lz_uid'] = $lz_uid;
	}

	public function setCz_id(int $cz_id) {
		$this->info['cz_id'] = $cz_id;
	}

	public function setRid(int $rid) {
		$this->info['rid'] = $rid;
	}

	public function setRuid(int $ruid) {
		$this->info['ruid'] = $ruid;
	}

	public function setTime(int $time) {
		$this->info['time'] = $time;
	}

	public function setContent(String $content) {
		$this->info['content'] = $content;
	}

	public function getLz_id() {
		return $this->info['lz_id'];
	}

	public function getLz_uid() {
		return $this->info['lz_uid'];
	}

	public function getCz_id() {
		return $this->info['cz_id'];
	}

	public function getRid() {
		return $this->info['rid'];
	}

	public function getRuid() {
		return $this->info['ruid'];
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
		return ['lz_id', 'lz_uid', 'cz_id', 'rid'];
	}

	public function getOtherKey() {
		return ['ruid', 'time', 'content'];
	}
}
?>