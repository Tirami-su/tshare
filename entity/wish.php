<?php
/************ 心愿单 *************/
include_once("entity.php");

/**
 *       字段           类型      主键    默认值    允许为空     描述
 * |      id       |    int    |  是   |   无   |    否     | 商品编号
 * |     email     |  varchar  |  否   |   无   |    否     | 发起者
 * |     name      |  varchar  |  否   |   无   |    否     | 商品名称
 * |     time      |    int    |  否   |   无   |    否     | 发布时间
 * |    is_get     |    int    |  否   |   0    |    否     | 是否已经得到(0为暂未被收购)
 * |    delete     |    int    |  否   |   0    |    否     | 是否删除(0为暂未被删除)
 */
class wish implements entity {
	private $info;

	public function __construct() {}

	public function set($info) {
		$this->info = $info;
	}

	public function setId(int $id) {
		$this->info['id'] = $id;
	}

	public function setEmail(String $email) {
		$this->info['email'] = $email;
	}

	public function setName(String $name) {
		$this->info['name'] = $name;
	}

	public function setTime(int $time) {
		$this->info['time'] = $time;
	}

	public function setIs_get(int $is_get) {
		$this->info['is_get'] = $is_get;
	}

	public function setDelete(int $delete) {
		$this->info['delete'] = $delete;
	}

	public function getId() {
		return $this->info['id'];
	}

	public function getEmail() {
		return $this->info['email'];
	}

	public function getName() {
		return $this->info['name'];
	}

	public function getTime() {
		return $this->info['time'];
	}

	public function getIs_get() {
		return $this->info['is_get'];
	}

	public function getDelete() {
		return $this->info['delete'];
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
		return ['id'];
	}

	public function getOtherKey() {
		return ['email', 'name', 'price', 'time', 'is_get', 'delete'];
	}
}
?>