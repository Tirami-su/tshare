<?php
require_once '../phpsocket.io/vendor/autoload.php';
use Workerman\Worker;
use PHPSocketIO\SocketIO;


$onlineUsers = array();		// 全局数组保存所有在线的用户id


// 创建socket.io服务端，监听3120端口
$io = new SocketIO(3120);
// 当有客户端连接时
$io->on('connection', function($socket)use($io){
  	// 保存这个套接字到数组（根据id保存）
  	$socket->on('login', function($id)use($socket) {
  		global $onlineUsers;
  		if(isset($onlineUsers[$id])) {
  			// 用户已经登录
  			echo "用户已经登录\n";
  			return;
  		}

  		$onlineUsers[$id] = $socket;
  		$socket->uid = $id;
  		
  		echo "登录：". $id."\n";
  	});

  	$socket->on('disconnect', function()use($socket) {
  		global $onlineUsers;
  		if(!isset($onlineUsers[$socket->uid])) {
             return;
        } else {
        	unset($onlineUsers[$socket->uid]);
        }

        echo "退出：". $socket->uid."\n";
  	});
});


// 开启一个http监听端口，通过这个端口可以向指定id的客户端发送消息
$io->on('workerStart', function()use($io){
	$inner_http_worker = new Worker("http://www.haoye.com:3121");
	$inner_http_worker->onMessage = function($http_connection, $data) {
		global $onlineUsers;
		$_POST = $_POST ? $_POST : $_GET;

		/**
		 * 推送的数据格式type=publish&to=id$content=xxx
		 * 也就是说参数$data是一个关联数组，有三个元素
		 * 第一个元素：'type'    = "publish"
		 * 第二个元素：'to'      = $id
		 * 第三个元素：'content' = '消息内容'
		 */
		global $io;
		$to = $_POST['to'];
		$_POST['content'] = htmlspecialchars($_POST['content']);
		if($to) {
			// 如果有指定的id，则向该id所对应的客户端发送消息
			$onlineUsers[$to]->emit('new_msg', $_POST['content']);
		} else {
			// 向所有客户端发送消息
			$io->emit('new_msg', $_POST['content']);
		}

		// http接口返回，如果用户离线，先将消息保存到信箱，等用户上线再发送
        if($to && !isset($onlineUsers[$to])){
            // return $http_connection->send('offline');
            // 保存到信箱（数据库中）

        }else{
            return $http_connection->send('ok');
        }
	};
	$inner_http_worker->listen();
});

Worker::runAll();
?>