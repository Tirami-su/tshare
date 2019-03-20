<?php
/******************* 文件实体类 ***********************/

/**
 * 对应于数据库中的file实体表
 *    字段       类型       主键    默认值    允许为空       描述
 * |   id    |   int     |  是   |   无   |    否     |    学号
 * |filename |  varchar  |  是   |   无   |    否     |    文件名
 * |  path   |  varchar  |  否   |   无   |    否     |   文件路径
 * |  type   |  varchar  |  否   |   无   |    是     |   文件分类
 * |  time   |   int     |  否   |   无   |    否     |   上传时间
 */
class file implements entity {
	/*上传文件信息*/
	private $info;

	public function __construct(){}

	/**
	 * 设置上传文件信息
	 * @param array $info 上传文件信息（关联数组）
	 */
	public function set($info) {
		$this->info = $info;
	}

	public function setId(int $id) {
		$this->info['id'] = $id;
	}

	public function setFilename(int $filename) {
		$this->info['filename'] = $filename;
	}

	public function setPath(int $path) {
		$this->info['path'] = $path;
	}

	public function setType(int $type) {
		$this->info['type'] = $type;
	}

	public function setTime(int $time) {
		$this->info['time'] = $time;
	}

	public function getId() {
		return $this->info['id'];
	}

	public function getFilename() {
		return $this->info['filename'];
	}

	public function getPath() {
		return $this->info['path'];
	}

	public function getType() {
		return $this->info['type'];
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
		return ['username', 'password', 'status'];
	}
}

?>