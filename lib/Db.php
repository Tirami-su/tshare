<?php

class Db extends mysqli{
	// 数据库主机名
	private $host = '127.0.0.1';
	// 数据库用户名
	private $user = 'root';
	// 数据库密码
	private $pwd = '';
	// 数据库名
	private $dbname = '';

	public function __construct() {
		parent::__construct($this->host, $this->user, $this->pwd, $this->dbname);
	}
}
?>