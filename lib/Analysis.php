<?php
/*************** 分词器 *******************/
include_once(dirname(__FILE__) . "/PHPAnalysis/PHPAnalysis.class.php");
include_once("Db.php");
include_once(dirname(__FILE__) . "/../entity/dict.php");

/**
 * 分词器，只对中文进行分词
 */
class Analysis extends PhpAnalysis {
	public function __construct() {
		parent::__construct();
	}

	/**
	 * 开始执行分析
	 * @param bool $optimize 是否对结果进行优化
	 * @param bool $isAdd 是否将结果中的关键字加入词典
	 * @return bool
	 */
	public function StartAnalysis($optimize = true, $isAdd = false) {
		parent::StartAnalysis($optimize);

		if($isAdd === true) {
			// 根据分词结果加入新的单词
			$keywords = $this->getFinallyKeyWords();	// 关键字数组
			$keywords = explode(",", $keywords);
			$index = $this->getFinallyIndex();			// 分词索引

			foreach ($keywords as $value) {
				if($index[$value] >= 3) {
					$this->addWord($value);
				}			
			}
		}		
	}

	/**
	 * 向词典中添加单词，如果单词已经存在，那么增加词频
	 * @param String $str 一个单词
	 */
	public function addWord(String $str) {
		$db = new Db();
		$res = $db->select("dict", ['w' => $str]);
		if($res === NULL) {
			// 如果这个单词不存在，那么添加新的单词
			$dict = new dict();
			$dict->set(['w' => $str, 'r' => 0, 'a' => 'n']);
			$db->insert("dict", $dict);
		} else {
			// 如果单词已经存在，那么增加词频
			$res->setR($res->getR()+1);
			$db->update("dict", $res);
		}
	}

	/**
	 * 添加多个单词到词典
	 * @param array 由多个单词组成的数组
	 */
	public function addWords($arr) {
		foreach ($arr as $value) {
			self::addWord($value);
		}
	}

	/**
	 * 设置词典更新标志和更新时间
	 */
	public function updateDict() {
		$db = new Db();
		$dict = $db->select("dict", ['w' => 'update']);
		if(time() - $dict->getR() > 12*3600) {
			// 更新词典
			$res = $db->selects("dict", ['a' => 'n']);
			$this->MakeDict($res);

			// 修改更新时间
			// $dict->setR(time());
			// $db->update("dict", $dict);
		}
	}
}
?>