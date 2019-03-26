<?php
/************* 词典实体表 **************/
include_once("entity.php");

/**
 * 对应于数据库中的dict实体表
 *    字段       类型       主键     默认值    允许为空      描述
 * |   w    |  varchar  |   是   |    无   |    否     |   词汇
 * |   r    |    int    |   否   |    无   |    否     |   词频
 * |   a    |  varchar  |   否   |   'n'   |    否     |  词语类型（前缀词、后缀词）
 * 表中有一条数据标识着词库是否更新（a='m'这一项，其次r字段表示上一次更新词库的时间）
 */
class dict implements entity {
	private $info;

	public function __construct() {}

	public function set($info) {
		$this->info = $info;
	}

	public function setW(String $w) {
		$this->info['w'] = $w;
	}

	public function setR(int $r) {
		$this->info['r'] = $r;
	}

	public function setA(String $a) {
		$this->info['a'] = $a;
	}

	public function getW() {
		return $this->info['w'];
	}

	public function getR() {
		return $this->info['r'];
	}

	public function getA() {
		return $this->info['a'];
	}



/****************** entity接口方法 ********************/

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
		return ['w'];
	}

	public function getOtherKey() {
		return ['r', 'a'];
	}
}
?>