<?php
/*************** 实体类的接口 ***************/
/*************** 所有的实体类的命名都必须与实体表名称相同 ***************/
/*************** 实体类中所有的属性名称必须与实体表的字段名称相同 ***************/

interface entity {
	/**
	 * 根据字段名称获取值
	 * @param String $name 字段名称
	 * @return 属性值（可以是各种类型）
	 */
	public function getAttribute($name);

	/**
	 * 获取由所有的字段和属性值组成的关联数组
	 * @return array[$name => $value]
	 */
	public function getAttributes();

	/**
	 * 获取当前实体表的主键（联合主键）
	 * @return array 联合主键的字段名组成的索引数组，如果主键只有一个那么数组就只有一个元素（所有的元素都是字符串类型）
	 */
	public function getPrimaryKey();

	/**
	 * 获取当前实体表除主键以外其他字段名称
	 * @return array 一个索引数组（所有元素均为字符串类型）
	 */
	public function getOtherKey();
}

?>