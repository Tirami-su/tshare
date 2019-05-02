<?php
/************* 关注表 ***************/
include_once("entity");

/**
 *       字段           类型      主键    默认值    允许为空     描述
 * |      id       |    int    |  是   |   无   |    否     | 商品编号
 * |     email     |  varchar  |  否   |   无   |    否     | 关注者
 */
class attention implements entity {
	private $info;

	public function __construct() {}

	public function setId(int $id) {
		$this->info['id'] = $id;
	}

	public function setEmail(String $email) {
		$this->info['email'] = $email;
	}

	public function getId() {
		return $this->info['id'];
	}

	public function getEmail() {
		return $this->info['email'];
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
		return ['id', 'email'];
	}

	public function getOtherKey() {
		return [];
	}
}
?>