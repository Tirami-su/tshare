<?php
/************* 物品收购表 **************/
include_once("entity.php");

/**
 *       字段          类型      主键     默认值    允许为空     描述
 * |      id      |    int    |  是   |    无   |    否     | 物品编号
 * |     email    |  varchar  |  否   |    无   |    否     | 出售者邮箱
 * |    objName   |  varchar  |  否   |    无   |    否     | 物品名称
 * |objDescription|  varchar  |  否   |    无   |    否     | 物品描述
 * |    price     |   float   |  否   |    无   |    否     | 预期价格
 * |    isBuy     |    int    |  否   |    0    |    否     | 是否完成交易
 */
class buy implements entity {
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

	public function setObjName(String $objName) {
		$this->info['objName'] = $objName;
	}

	public function setObjDescription(String $objDescription) {
		$this->info['objDescription'] = $objDescription;
	}

	public function setPrice(float $price) {
		$this->info['price'] = $price;
	}

	public function setIsBuy(int $isBuy) {
		$this->info['isBuy'] = $isBuy;
	}

	public function getId() {
		return $this->info['id'];
	}

	public function getEmail() {
		return $this->info['email'];
	}

	public function getObjName() {
		return $this->info['objName'];
	}

	public function getObjDescription() {
		return $this->info['objDescription'];
	}

	public function getPrice() {
		return $this->info['price'];
	}

	public function getIsBuy() {
		return $this->info['isBuy'];
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
		return ['email', 'objName', 'objDescription', 'price', 'isBuy'];
	}
}
?>