<?php

class Db {
	// 数据库主机名
	private $host = '127.0.0.1';

	// 数据库用户名
	private $user = 'root';

	// 数据库密码
	private $pwd = '';

	// 数据库名
	private $dbname = '';

	// Db类对象
	protected $db;

	protected function __construct() {
		$this->init();
	}

	/**
	 * 获取该类的实例化对象
	 */
	public static function instance() {
		if(is_null(self::$db)) {
			self::$db = new static();
		}
		return self::$db;
	}

	private function init() {
		$this->db = new mysqli($this->host, $this->user, $this->pwd, $this->dbname);
		if($this->db->connect_errno <> 0) {
			echo '连接失败！';
			echo $this->db->connect_error;
			exit;
		}
	}

}

?>