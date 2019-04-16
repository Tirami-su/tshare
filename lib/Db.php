<?php

require_once dirname(__FILE__) .'/../entity/entity.php';		// 导入实体类
require_once dirname(__FILE__) .'/../entity/EntityFactory.php';	// 导入实体工厂类
$config = require_once dirname(__FILE__) .'/../config.php';

/**
 * 数据库类
 */
class Db extends mysqli{
	public function __construct() {
		global $config;
		$host 	= $config['database']['host'];
		$user 	= $config['database']['user'];
		$pwd 	= $config['database']['pwd'];
		$dbname = $config['database']['dbname'];
		parent::__construct($host, $user, $pwd, $dbname);
	}

	/**
	 * 数据库插入操作，向数据库中插入一个实体对象
	 * @param String $table 表名称
	 * @param entity $entity 实体对象
	 * @return bool 插入成功返回true，插入失败返回false
	 */
	public function insert(String $table, entity $entity) {
		$info = $entity->getAttributes();
		$key = "";		// 字段名称
		$value = "";	// 属性值
		foreach ($info as $k => $v) {
			// 生成sql语句的必要组成部分
			$key .= "{$k},";
			if($v === NULL) {
				$value .= "NULL,";
			} else {
				$value .= "'{$v}',";
			}			
		}

		$key = substr($key, 0, -1);			// 删除多余的","
		$value = substr($value, 0, -1);		// 删除多余的","

		$sql = "insert into $table($key) values($value)";
		
		$flag = $this->query($sql);
		return $flag;
	}

	/**
	 * 删除数据库表中的一个实体对象（根据主键删除）
	 * @param String $table 表名称
	 * @param entity $entity 实体对象
	 * @return bool 删除成功返回true，删除失败返回false
	 */
	public function delete(String $table, entity $entity) {
		$condition = "";

		$primary = $entity->getPrimaryKey();		// 获取主键名称
		foreach ($primary as $key) {
			// 生成删除条件
			$value = $entity->getAttribute($key);	// 获取主键的值
			$condition .= "{$key}='{$value}' and ";
		}

		$condition = substr($condition, 0, -5);

		$sql = "delete from {$table} where {$condition}";
		$flag = $this->query($sql);
		return $flag;
	}

	/**
	 * 修改数据表中的实体对象（根据主键修改）
	 * @param String $table 表名称
	 * @param entity $entity 实体对象
	 * @return bool 修改成功返回true，修改失败返回false
	 */
	public function update(String $table, entity $entity) {
		$content = "";		// 待修改的键值对
		$condition = "";	// 修改条件

		$primary = $entity->getPrimaryKey();
		$other = $entity->getOtherKey();

		foreach ($primary as $key) {
			// 生成修改条件
			$value = $entity->getAttribute($key);	// 获取主键的值
			$condition .= "{$key}='{$value}' and ";
		}
		$condition = substr($condition, 0, -5);

		foreach ($other as $key) {
			$value = $entity->getAttribute($key);
			if($value === NULL) {
				$content .= "{$key}=NULL,";
			} else {
				$content .= "{$key}='{$value}',";
			}			
		}
		$content = substr($content, 0, -1);

		$sql = "update {$table} set {$content} where {$condition}";
		$flag = $this->query($sql);

		return $flag;
	}

	/**
	 * 重载update方法，不依据主键进行更新
	 * @param String $table 表名称
	 * @param array $condition 条件
	 * @param array $value 更新目标
	 */
	public function update_reload(String $table, $condition, $values) {
		// 生成修改条件
		$cond = "";
		foreach ($condition as $key => $value) {
			$cond .= "{$key}='{$value}' and ";
		}
		$cond = substr($cond, 0, -5);

		// 生成修改目标
		$kv = "";
		foreach ($values as $k => $v) {
			$kv .= "{$k}='{$v}',";
		}
		$kv = substr($kv, 0, -1);

		$sql = "update {$table} set {$kv} where {$cond}";
		$flag = $this->query($sql);
		return $flag;
	}

	/**
	 * 查询数据库表中的一个实体对象（根据主键）
	 * @param String $table 表名称
	 * @param array $primaty 数据表中主键与值的关联数组
	 * @return entity 查询成功返回实体对象，失败返回NULL
	 */
	public function select(String $table, $primary) {
		$instance = EntityFactory::getInstance($table);		// 根据实体表名称获得实体类的实例化对象

		$condition = "";
		foreach ($primary as $key => $value) {
			// 生成选择条件
			$condition .= "{$key}='{$value}' and ";
		}
		$condition = substr($condition, 0, -5);

		$sql = "select * from {$table} where {$condition}";
		$res = $this->query($sql);
		if($res->num_rows === 0) {
			return NULL;
		} else {
			$row = $res->fetch_array(MYSQLI_ASSOC);
			$instance->set($row);
			return $instance;
		}
	}

	/**
	 * 查询数据库表（不根据主键）
	 * @param String $table 表名称
	 * @param array condition 查询条件
	 * @return entity[] 返回一个实体对象的数组（因为不根据主键查询可能会有多条记录）
	 */
	public function selects(String $table, $condition) {
		$arr = [];		// 返回一个数组

		$cond = "";
		foreach ($condition as $key => $value) {
			// 生成选择条件
			if($value === NULL) {
				$cond .= "{$key} is null and ";
			} else {
				$cond .= "{$key}='{$value}' and ";
			}
		}
		$cond = substr($cond, 0, -5);

		$sql = "select * from {$table} where {$cond}";
		$res = $this->query($sql);

		if($res->num_rows === 0) {
			// 没有查询到任何记录返回空
			return NULL;
		} else {
			while($row = $res->fetch_array(MYSQLI_ASSOC)) {
				$instance = EntityFactory::getInstance($table);		// 根据实体表名称获得实体类的实例化对象
				$instance->set($row);
				$arr[] = $instance;
			}
			return $arr;
		}
	}

	/**
	 * 根据类别和关键字查询文件
	 * @param String $key 关键字
	 * @param String $field 字段名称
	 * @param bool $onlyFile 是否只查询文件而不查询文件夹
	 */
	public function findFile(String $key, String $field, bool $onlyFile=true) {
		$arr = array();

		$sql = "select * from file where {$field} like '%{$key}%'";
		if($onlyFile === true) {
			$sql .= " and is_dir=0";
		}
		
		$res = $this->query($sql);

		if($res->num_rows === 0){
			return NULL;
		} else {
			while($row = $res->fetch_array(MYSQLI_ASSOC)) {
				$instance = EntityFactory::getInstance("file");
				$instance->set($row);
				$arr[] = $instance;
			}
			return $arr;
		}
	}

	/**
	 * 获取某张数据表中数据的条数
	 * @param String $table 表名称
	 */
	public function count(String $table) {
		$sql = "select count(*) count from $table";
		$res = $this->query($sql);

		$count = $res->fetch_array(MYSQLI_ASSOC);
		return $count['count'];
	}
}
?>