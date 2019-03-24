<?php
/*****************实体对象的工厂类***********************/
class EntityFactory {
	/**
	 * 根据子类名获取子类对象
	 */
	public static function getInstance(String $className) {
		// 通过反射实例化对象
		$class = new ReflectionClass($className);
		$instance = $class->newInstance();
		return $instance;
	}
}

?>