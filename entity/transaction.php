<?php
/*************** 交易记录 *****************/
include_once("entity.php");

/**
 *       字段          类型      主键     默认值    允许为空     描述
 * |     tid      |    int    |  是   |    无   |    否     | 交易编号
 * |     gid      |    int    |  否   |    无   |    否     | 商品编号
 * |    email     |  varchar  |  否   |    无   |    否     | 接收交易人
 * |   success    |    int    |  否   |    0    |    否     | 交易是否成功
 * |  failReason  |  varchar  |  否   |    无   |    是     | 失败原因
 * |     time     |    int    |  否   |    无   |    否     | 交易时间
 */
class transaction implements entity {

	private $info;

	public function set($info) {
		$this->info = $info;
	}

	public function setTid(int $tid) {
		$this->info['tid'] = $tid;
	}

	public function setGid(int $gid) {
		$this->info['gid'] = $gid;
	}

	public function setEmail(String $Email) {
		$this->info['email'] = $email;
	}

	public function setSuccess(int $success) {
		$this->info['success'] = $success;
	}

	public function setFailReason(String $failReason) {
		$this->info['failReason'] = $failReason;
	}

	public function setTime(int $time) {
		$this->info['time'] = $time;
	}

	public function getTid() {
		return $this->info['tid'];
	}

	public function getGid() {
		return $this->info['gid'];
	}

	public function getEmail() {
		return $this->info['email'];
	}

	public function getSuccess() {
		return $this->info['success'];
	}

	public function getFailReason() {
		return $this->info['failReason'];
	}

	public function getTime() {
		return $this->info['time'];
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
		return ['tid'];
	}

	public function getOtherKey() {
		return ['gid', 'email', 'success', 'failReason', 'time'];
	}
}

?>