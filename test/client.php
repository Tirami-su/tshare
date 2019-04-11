<?php
include_once("../lib/socket/send.php");

$sender = $_POST['from'];
$address = $_POST['to'];
$content = $_POST['content'];

// $sender = "";
// $address = "";
// $content = "asdf";

echo json_encode(send($sender, $address, $content));
// var_dump(send($sender, $address, $content));
?>