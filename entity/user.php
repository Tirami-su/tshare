<?php
/**************** 用户实体类 *****************/
include_once("entity.php");

/**
 * 对应于数据库中的user实体表
 *      字段          类型      主键     默认值    允许为空     描述
 * |    email    |  varchar  |  是   |    无   |    否     |   学号
 * |   username  |  varchar  |  否   |    无   |    否     |   昵称
 * |   password  |  varchar  |  否   |    无   |    否     |   密码
 * |cookie_encode|  varchar  |  否   |    0    |    否     | cookie加密密钥
 * |cookie_decode|  varchar  |  否   |    0    |    否     | cookie解密密钥
 * | login_time  |    int    |  否   |    0    |    否     | 登录时间
 * | logout_time |    int    |  否   |    0    |    否     | 退出时间
 * | session_id  |    int    |  否   |    0    |    否     | 标识登录者
 * |    money    |    int    |  否   |    无   |    否     |  用户积分
 */
class user implements entity {

	/* 用户信息（学号、昵称、密码、登录状态） */
	private $info;	// 关联数组类型，数组的键对应于数据库表中的字段名称

	/**
	 * 构造方法
	 */
	public function __construct() {
	}

	/**
	 * 设置用户信息
	 * @param array $info 用户信息（关联数组）
	 */
	public function set($info) {
		$this->info = $info;
	}

	public function setEmail(String $email) {
		$this->info['email'] = $email;
	}

	public function setUsername(String $username) {
		$this->info['username'] = $username;
	}

	public function setPassword(String $password) {
		$this->info['password'] = $password;
	}

	public function setCookie_encode(String $encode) {
		$this->info['cookie_encode'] = $encode;
	}

	public function setCookie_decode(String $decode) {
		$this->info['cookie_decode'] = $decode;
	}

	public function setLogin_time(int $login_time) {
		$this->info['login_time'] = $login_time;
	}

	public function setLogout_time(int $logout_time) {
		$this->info['logout_time'] = $logout_time;
	}

	public function setSession_id(int $session_id) {
		$this->info['session_id'] = $session_id;
	}

	public function setMoney(int $money) {
		$this->info['money'] = $money;
	}

	public function getEmail() {
		return $this->info['email'];
	}

	public function getUsername() {
		return $this->info['username'];
	}

	public function getPassword() {
		return $this->info['password'];
	}

	public function getCookie_encode() {
		return $this->info['cookie_encode'];
	}

	public function getCookie_decode() {
		return $this->info['cookie_decode'];
	}

	public function getLogin_time() {
		return $this->info['login_time'];
	}

	public function getLogout_time() {
		return $this->info['logout_time'];
	}

	public function getSession_id() {
		return $this->info['session_id'];
	}

	public function getMoney() {
		return $this->info['money'];
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
		return ['email'];
	}

	public function getOtherKey() {
		return ['username', 'password', 'cookie_encode', 'cookie_decode', 'login_time', 'logout_time', 'session_id', 'money'];
	}
}


?>