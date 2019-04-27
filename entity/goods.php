<?php
/************ 商品类 **************/
include_once("entity.php");

/**
 *       字段           类型      主键     默认值    允许为空     描述
 * |      id       |    int    |  是   |    无   |    否     | 商品编号
 * |     email     |  varchar  |  否   |    无   |    否     | 发起者
 * |     name      |  varchar  |  否   |    无   |    否     | 商品名称
 * |     price     |   float   |  否   |    无   |    否     | 商品预期价格
 * |    picture    |  varchar  |  否   |    无   |    是     | 商品图片路径
 * |  description  |  varchar  |  否   |    无   |    否     | 商品描述
 * |     isSell    |    int    |  否   |    无   |    否     | 收购还是出售
 * |    success    |    int    |  否   |    0    |    否     | 商品是否交易成功
 * |     time      |    int    |  否   |    无   |    否     | 商品上架时间
 */
class goods implements entity {
	private $info;

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

	public function setPrice(float $price) {
		$this->info['price'] = $price;
	}

	public function setPicture(String $picture) {
		$this->info['picture'] = $picture;
	}

	public function setDescription(String $description) {
		$this->info['description'] = $description;
	}

	public function setIsSell(int $isSell) {
		$this->info['isSell'] = $isSell;
	}

	public function setSuccess(int $success) {
		$this->info['success'] = $success;
	}

	public function setTime(int $time) {
		$this->info['time'] = $time;
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

	public function getPrice() {
		return $this->info['price'];
	}

	public function getPicture() {
		return $this->info['picture'];
	}

	public function getDescription() {
		return $this->info['description'];
	}

	public function getIsSell() {
		return $this->info['isSell'];
	}

	public function getSuccess() {
		return $this->info['success'];
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
		return ['id'];
	}

	public function getOtherKey() {
		return ['email', 'name', 'price', 'picture', 'description', 'isSell', 'success', 'time'];
	}
}
?>