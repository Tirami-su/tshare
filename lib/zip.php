<?php

class zip {
	public function __construct(){
       //init code here...
       header("content-type:text/html;charset=utf8");
   	}

   	/**
   	 * 打包压缩文件夹到指定目录
   	 * @param String $src_dir 待压缩文件夹路径(xxx/xxx/xxx)
   	 * @param String $dest_file 压缩目标文件路径(xxx/xxx.zip)
   	 * @return bool 压缩成功或失败
   	 */
   	public function compress_dir($src_dir, $dest_file=false) {
 		// 获取路径的叶子结点文件夹名称
 		if(substr($src_dir, -1) <> "/") {
 			$src_dir .= "/";
 		}
 		$list = explode("/", $src_dir);
 		$zipname = $list[count($list)-2];

 		if($dest_file===false) {
 			// 没有设置目标压缩文件名，则以文件夹名称代替
 			$dest_file = $zipname.".zip";
 		}

 		$zip = new ZipArchive();
 		$res = $zip->open($dest_file, ZipArchive::CREATE);
 		if($res === true) {
 			$this->addDirToZip($src_dir, $zip);
 			$zip->close();
 			return true;
 		} else {
 			return false;
 		}
   	}

   	/**
   	 * 打包压缩多个文件到压缩包
   	 * @param String $dir 多个文件的共同父目录(xxx/xxx)
   	 * @param array $filenames 文件名(xxx.xx)
   	 * @param String dest_file 压缩目标文件路径(xxx/xxx.zip)
   	 * @return bool 压缩成功或失败
   	 */
   	public function compress_files($dir, $filenames, $dest_file=false) {
   		if(substr($dir, -1) <> "/") {
   			$dir .= "/";
   		}

   		$list = explode("/", $dir);
 		$zipname = $list[count($list)-2];

 		if($dest_file === false) {
 			$dest_file = $zipname . ".zip";
 		}

 		$zip = new ZipArchive();
 		$res = $zip->open($dest_file, ZipArchive::CREATE);
 		if($res === true) {
 			foreach ($filenames as $filename) {
 				$zip->addFile($dir.$filename);
 			}
 			$zip->close();
 			return true;
 		} else {
 			return false;
 		}
   	}

   	/**
    * 解压文件到指定目录
    * @param $src_file 				string   zip压缩文件的路径
    * @param $dest_dir 				string   解压文件的目的路径
    * @param $create_zip_name_dir 	boolean  是否以压缩文件的名字创建目标文件夹
    * @param $overwrite				boolean  是否重写已经存在的文件
    * @return  boolean  返回成功或失败
    */
   	public function decompress($src_file, $dest_dir=false, $create_zip_name_dir=true, $overwrite=true) {
   		if ($zip = zip_open($src_file)){
	        if ($zip){
	            $splitter = ($create_zip_name_dir === true) ? "." : "/";
	            if($dest_dir === false){
	                $dest_dir = substr($src_file, 0, strrpos($src_file, $splitter))."/";
	            } else if(substr($dest_dir, -1) <> '/') {
	            	// 统一处理目的路径末尾是否带"/"的问题
	            	$dest_dir .= '/';
	            }

	            // 如果不存在 创建目标解压目录
	            $this->create_dirs($dest_dir);
	             // 对每个文件进行解压
	            while ($zip_entry = zip_read($zip)){
	                // 文件不在根目录
	                $pos_last_slash = strrpos(zip_entry_name($zip_entry), "/");
	                if ($pos_last_slash !== false){
	                    // 创建目录 在末尾带 /
	                    $this->create_dirs($dest_dir.substr(zip_entry_name($zip_entry), 0, $pos_last_slash+1));
	                }
	                // 打开包
	                if (zip_entry_open($zip, $zip_entry, "r")) {
	                    // 文件名保存在磁盘上
	                    $file_name = $dest_dir.zip_entry_name($zip_entry);
	                    // 检查文件是否需要重写
	                    if ($overwrite === true || $overwrite === false && !is_file($file_name)){
	                        // 读取压缩文件的内容
	                        $fstream = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
	                        @file_put_contents($file_name, $fstream);
	                        // 设置权限
	                        chmod($file_name, 0777);
	                    }
	                    // 关闭入口
	                    zip_entry_close($zip_entry);
	                }
	            }
	            // 关闭压缩包
	            zip_close($zip);
	        }
        }else{
            return false;
        }
        return true;
   	}


   	/**
	 * 递归的将文件夹中的内容压缩到压缩包中
	 * @param String $src_dir 待压缩的文件夹
	 * @param ZipArchive $zip ZipArchive资源对象
	 */
    private function addDirToZip($src_dir, $zip) {
    	// 递归压缩文件夹
 		$handle = opendir($src_dir);
 		while(($filename = readdir($handle)) !== false) {
 			if($filename != "." && $filename != "..") {
 				// 不是当前目录，也不是上级目录
 				if(is_dir($src_dir.$filename)) {
 					// 如果这是个文件夹，那么递归进行压缩
 					$this->addDirToZip($src_dir.$filename."/", $zip);
 				} else {
 					// 如果是文件，直接压缩
 					$zip->addFile($src_dir.$filename, $filename);
 				}
 			}
 		}
		closedir($handle);
    }

    /**
     * 递归创建目录
     */
    private function create_dirs($path){
		if (!is_dir($path)){
			$directory_path = "";
			$directories = explode("/",$path);
			array_pop($directories);
			foreach($directories as $directory){
				$directory_path .= $directory."/";
				if (!is_dir($directory_path)){
					mkdir($directory_path);
					chmod($directory_path, 0777);
				}
			}
		}
    }
}
?>