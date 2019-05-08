<?php
require_once '../phpsocket.io/vendor/autoload.php';
require_once "../../entity/notice.php";
require_once "../../entity/noticeTemp.php";
require_once "../../entity/user.php";
require_once "../Db.php";
use Workerman\Worker;
use PHPSocketIO\SocketIO;

$onlineUsers = array();		// 保存所有在线用户信息
$db = new Db();

// 创建socket.io服务端，监听3120端口
$server = new SocketIO(3120);
// 当有客户端连接时
$server->on('connection', function($socket)use($server) {
	// 给客户端创建一个登录事件
	$socket->on('login', function($email)use($socket) {
		// 保存套接字
		global $onlineUsers, $db;
		if(isset($onlineUsers[$email])) {
			return;
		}
		$onlineUsers[$email] = $socket;
		$socket->uid = $email;

		// 查询是否有发送给自己的消息
		// 将来需要修改：只有当用户查看过该消息后才修改标志位
		$notices = $db->selects("notice", ['address' => $email]);
		if($notices !== NULL) {
			$arr = array();
			for($i=0;$i<count($notices);$i++) {
				$notice = $notices[$i];
				if($notice->getReceived() == 0) {
					$arr['sender'] = $notice->getSender();
					$arr['address'] = $notice->getAddress();
					$arr['content'] = $notice->getContent();
					$arr['time'] = Date("Y-m-d H:i:s", $notice->getTime());

					$socket->emit("new_msg", json_encode($arr));
					// 修改标志位
					$notice->setReceived(1);
					$db->update("notice", $notice);
				}				
			}
		}

		// 查询是否有发送给全体用户的消息
		// 将来需要修改：只有用户查看消息后才从noticeTemp表中删除记录
		$notices = $db->selects("notice", ['address' => NULL]);
		if($notices !== NULL) {
			$arr = array();
			for($i=0;$i<count($notices);$i++) {
				$notice = $notices[$i];
				if($notice->getReceived() == 0) {
					$nid = $notice->getNid();
					$temp = $db->select("noticeTemp", ['nid' => $nid, 'address' => $email]);
					if($temp !== NULL) {
						$arr['sender'] = $notice->getSender();
						$arr['address'] = $notice->getAddress();
						$arr['content'] = $notice->getContent();
						$arr['time'] = Date("Y-m-d H:i:s", $notice->getTime());
						$socket->emit("new_msg", json_encode($arr));

						// 从noticeTemp表中删除改条记录
						$db->delete("noticeTemp", $temp);

						// 如果该编号的消息全部发送完毕，需要修改标志位
						$res = $db->selects("noticeTemp", ['nid' => $nid]);
						if($res === NULL) {
							$notice->setReceived(1);
							$db->update("notice", $notice);
						}
					}
				}				
			}
		}

		/******* 调试 ********/
		echo "登录：" . $email . "\n";
		/******* 调试 ********/
	});

	// 当客户端断开连接时
	$socket->on("disconnect", function()use($socket) {
		global $onlineUsers;
		if(!isset($onlineUsers[$socket->uid])) {
			return;
		}

		unset($onlineUsers[$socket->uid]);

		/******* 调试 ********/
		echo "退出：" . $socket->uid . "\n";
		/******* 调试 ********/
	});
});

// 开启一个http监听端口，通过这个端口可以向指定用户发送消息
$server->on("workerStart", function()use($server) {
	global $config;
	$http_worker = new Worker("http://".$config['domain'].":3121");		// 使用3121端口进行消息监听
	$http_worker->onMessage = function($http_connection, $data) {
		global $onlineUsers;
		$_POST = $_POST ? $_POST : $_GET;

		global $server, $db;
		/**
		 * 发送消息的格式：type=xxx&data=notice
		 * type="notice"时表示向用户发送消息，消息的内容由data决定，data是一个notice关联数组，包含消息的四个属性：sender、address、content、time
		 */
		$type = $_POST['type'];
		if($type === "notice") {

			// 发送消息
			$notice = json_decode($_POST['data']);
			$arr = array();
			$arr['sender'] = $notice->sender;
			$arr['address'] = $notice->address;
			$arr['content'] = $notice->content;
			$arr['time'] = $notice->time;

			// 不论是否发送，都会将消息保存在数据库中
			$notice = new notice();
			$notice->set($arr);
			$db->insert("notice", $notice);
			$notice = $db->select("notice", ["nid" => $db->count("notice")]);

			$address = $notice->getAddress();		// 接收者
			if($address === NULL) {
				// 向所有用户发送消息
				$temp = new noticeTemp();
				$temp->setNid($notice->getNid());
				$allUsers = $db->selects("user", ["1" => "1"]);

				if($allUsers !== NULL) {
					for($i=0;$i<count($allUsers);$i++) {
						$email = $allUsers[$i]->getEmail();						
						$temp->setAddress($email);
						$db->insert("noticeTemp", $temp);
					}
				}

				foreach ($onlineUsers as $email => $socket) {
					$arr['time'] = Date('Y-m-d H:i:s', $arr['time']);
					$socket->emit("new_msg", json_encode($arr));
					$temp->setAddress($email);
					$db->delete("noticeTemp", $temp);
				}
				return $http_connection->send("success");
			} else {
				// 向指定用户发送消息
				if(isset($onlineUsers[$address])) {
					$arr['time'] = Date('Y-m-d H:i:s', $arr['time']);
					$onlineUsers[$address]->emit('new_msg', json_encode($arr));
					// 发送消息后修改标志位
					$notice->setReceived(1);
					$db->update("notice", $notice);
					return $http_connection->send("success");
				} else {
					// 用户不在线，先保存消息(这一步在最开始已经完成)
					return $http_connection->send("offline");
				}
			}
		}
	};
	$http_worker->listen();
});

Worker::runAll();
?>