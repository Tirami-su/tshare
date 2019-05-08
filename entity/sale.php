<?php
/************ 商品类 **************/
include_once("entity.php");

/**
 *       字段           类型      主键     默认值    允许为空     描述
 * |      id       |    int    |  是   |    无   |    否     | 商品编号
 * |     email     |  varchar  |  否   |    无   |    否     | 发起者
 * |     title     |  varchar  |  否   |    无   |    否     | 商品名称
 * |    category   |  varchar  |  否   |    无   |    否     | 商品分类
 * |     brand     |  varchar  |  否   |    无   |    是     | 商品品牌
 * |  description  |  varchar  |  否   |    无   |    否     | 商品描述
 * |   buy_price   |   float   |  否   |    无   |    是     | 买入价格
 * |    buy_way    |  varchar  |  否   |    无   |    是     | 买入方式
 * |      new      |    int    |  否   |    0    |    是     | 是否全新(1为全新)
 * |     price     |   float   |  否   |    无   |    否     | 预期价格
 * |    bargain    |    int    |  否   |    1    |    是     | 可否议价(0为不可议价)
 * |   delivery    |    int    |  否   |    0    |    是     | 是否配送(0为不配送)
 * |     view      |    int    |  否   |    0    |    否     | 浏览量
 * |    picture    |  varchar  |  否   |    无   |    是     | 参考图片路径
 * |     time      |    int    |  否   |    无   |    否     | 商品上架时间
 * |    is_sell    |    int    |  否   |    0    |    否     | 是否已经交易成功(0为暂未交易成功)
 * |    delete     |    int    |  否   |    0    |    否     | 是否删除(0为暂未删除)
 */
class sale implements entity {
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

	public function setTitle(String $title) {
		$this->info['title'] = $title;
	}

	public function setCategory(String $category) {
		$this->info['category'] = $category;
	}

	public function setBrand(String $brand) {
		$this->info['brand'] = $brand;
	}

	public function setDescription(String $description) {
		$this->info['description'] = $description;
	}

	public function setBuy_price(float $buy_price) {
		$this->info['buy_price'] = $buy_price;
	}

	public function setBuy_way(String $buy_way) {
		$this->info['buy_way'] = $buy_way;
	}

	public function setNew(int $new) {
		$this->info['new'] = $new;
	}

	public function setPrice(float $price) {
		$this->info['price'] = $price;
	}

	public function setBargain(int $bargain) {
		$this->info['bargain'] = $bargain;
	}

	public function setDelivery(int $delivery) {
		$this->info['delivery'] = $delivery;
	}

	public function setView(int $view) {
		$this->info['view'] = $view;
	}

	public function setPicture(String $picture) {
		$this->info['picture'] = $picture;
	}

	public function setTime(int $time) {
		$this->info['time'] = $time;
	}

	public function setIs_sell(int $is_sell) {
		$this->info['is_sell'] = $is_sell;
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

	public function getTitle() {
		return $this->info['title'];
	}

	public function getCategory() {
		return $this->info['category'];
	}

	public function getBrand() {
		return $this->info['brand'];
	}

	public function getDescription() {
		return $this->info['description'];
	}

	public function getBuy_price() {
		return $this->info['buy_price'];
	}

	public function getBuy_way() {
		return $this->info['buy_way'];
	}

	public function getNew() {
		return $this->info['new'];
	}

	public function getPrice() {
		return $this->info['price'];
	}

	public function getBargain() {
		return $this->info['bargain'];
	}

	public function getDelivery() {
		return $this->info['delivery'];
	}

	public function getView() {
		return $this->info['view'];
	}

	public function getPicture() {
		return $this->info['picture'];
	}

	public function getTime() {
		return $this->info['time'];
	}

	public function getIs_sell() {
		return $this->info['is_sell'];
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
		return ['email', 'title', 'category', 'brand', 'description', 'buy_price', 'buy_way', 'new', 'price', 'bargain', 'delivery', 'picture', 'time', 'is_sell', 'delete'];
	}
}
?>