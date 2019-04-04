<?php
/******************* 上传文件实体类 ***********************/
include_once("entity.php");
/**
 * 对应于数据库中的file实体表
 *        字段        类型       主键    默认值    允许为空       描述
 * 1.|    email   |   int     |  是   |   无   |    否     |     学号
 * 2.|  filename  |  varchar  |  是   |   无   |    否     |     文件名
 * 3.|    path    |  varchar  |  否   |   无   |    否     |   文件路径(以网站根目录起点)
 * 4.|    type    |  varchar  |  否   |   无   |    是     |   文件分类
 * 5.|upload_time |  varchar  |  否   |   无   |    否     |   上传时间
 * 6.|   subject  |  varchar  |  否   |   无   |    否     |     科目
 * 7.|  category  |   int     |  否   |   无   |    否     | 0课内/1课外（资料）
 * 8.|description |  varchar  |  否   |   无   |    否     |    资料描述
 * 9.|    time    |   int     |  否   |   无   |    是     | 资料针对的时间（试卷的年份）
 *10.|   score    |   int     |  否   |   0    |    否     |     评分
 *12.|  download  |   int     |  否   |   0    |    否     |     下载量
 *13.|   is_dir   |   int     |  否   |   0    |    否     |  是否为文件夹
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

	public function setEmail(String $email) {
		$this->info['email'] = $email;
	}

	public function setFilename(String $filename) {
		$this->info['filename'] = $filename;
	}

	public function setPath(String $path) {
		$this->info['path'] = $path;
	}

	public function setType(String $type) {
		$this->info['type'] = $type;
	}

	public function setUpload_time(String $upload_time) {
		$this->info['upload_time'] = $upload_time;
	}

	public function setSubject(String $subject) {
		$this->info['subject'] = $subject;
	}

	public function setCategory(int $category){
		$this->info['category'] = $category;
	}

	public function setDescription(String $description) {
		$this->info['description'] = $description;
	}

	public function setTime(int $time) {
		$this->info['time'] = $time;
	}

	public function setScore(int $score) {
		$this->info['score'] = $score;
	}

	public function setDownload(int $download) {
		$this->info['download'] = $download;
	}

	public function setIs_dir(int $is_dir) {
		$this->info['is_dir'] = $is_dir;
	}

	public function getEmail() {
		return $this->info['email'];
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

	public function getUpload_time() {
		return $this->info['upload_time'];
	}

	public function getSubject() {
		return $this->info['subject'];
	}

	public function getCategory() {
		return $this->info['category'];
	}

	public function getDescription() {
		return $this->info['description'];
	}

	public function getTime() {
		return $this->info['time'];
	}

	public function getScore() {
		return $this->info['score'];
	}

	public function getDownload() {
		return $this->info['download'];
	}

	public function getIs_dir() {
		return $this->info['is_dir'];
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
		return ['email', 'filename', 'path'];
	}

	public function getOtherKey() {
		return ['type', 'upload_time', 'subject', 'category', 'description', 'time', 'score', 'download', 'is_dir'];
	}
}

?>