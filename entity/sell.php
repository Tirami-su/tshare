<?php
/************ 物品出售表 ***************/
include_once("entity.php");

/**
 *       字段          类型      主键     默认值    允许为空     描述
 * |      id      |    int    |  是   |    无   |    否     | 物品编号
 * |     email    |  varchar  |  否   |    无   |    否     | 出售者邮箱
 * |    objName   |  varchar  |  否   |    无   |    否     | 物品名称
 * |  objPicture  |  varchar  |  否   |    无   |    否     | 物品图片路径
 * |objDescription|  varchar  |  否   |    无   |    否     | 物品描述
 * |    price     |   float   |  否   |    无   |    否     | 预期价格
 * |    isSell    |    int    |  否   |    0    |    否     | 是否完成交易
 */
class sell implements entity {
	private $info;

	public function sell() {}

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

	public function setObjPicture(String $objPicture) {
		$this->info['objPicture'] = $objPicture;
	}

	public function setObjDescription(String $objDescription) {
		$this->info['objDescription'] = $objDescription;
	}

	public function setPrice(float $price) {
		$this->info['price'] = $price;
	}

	public function setIsSell(int $isSell) {
		$this->info['isSell'] = $isSell;
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

	public function getObjPicture() {
		return $this->info['objPicture'];
	}

	public function getObjDescription() {
		return $this->info['objDescription'];
	}

	public function getPrice() {
		return $this->info['price'];
	}

	public function getIsSell() {
		return $this->info['isSell'];
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
		return ['email', 'objName', 'objPicture', 'objDescription', 'price', 'isSell'];
	}
}
}

?>