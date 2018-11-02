<?php
/**
 * Created by PhpStorm.
 * User: tioncico
 * Date: 18-4-27
 * Time: 下午9:10
 */
error_reporting(E_ALL);
$config = array(
    'host' => '192.168.159.129',
    'port' => 9601
);
//创建 TCP/IP socket
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket < 0) {
    die("socket创建失败原因: " . socket_strerror($socket) . "\n");
} else {
    echo "socket创建成功...\n";
}
$result = socket_connect($socket, $config['host'], $config['port']);
if ($result < 0) {
    die("SOCKET连接失败原因: ($result) " . socket_strerror($result) . "\n");
} else {
    echo "SOCKET连接成功...\n";
}
while (true) {
    echo "请输入您需要发送的数据\n";
    //发送命令
    $handle = fopen("php://stdin", "r");
    $s = fgets($handle);
    $s = pack('N', strlen($s)) . $s;
    socket_write($socket, $s, strlen($s));
    while ($out = socket_read($socket, 2048)) {
        echo "服务器响应:{$out}\n";
        break;
    }
    usleep(100);
}