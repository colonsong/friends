<?php

namespace App\Http\Controllers\Chat;


/**
 *
 * https://github.com/nnngu/WebSocketDemo-php/blob/master/server.php
 * https://github.com/nnngu/WebSocketDemo-js/blob/master/index.html
 *
 * client
 * GET /chat HTTP/1.1
 *  Host: server.example.com
 * Upgrade: websocket Connection:
 * Upgrade Sec-WebSocket-Key: x3JJHMbDL1EzLkh9GBhXDw==
 * Sec-WebSocket-Protocol: chat, superchat
 * Sec-WebSocket-Version: 13
 * Origin: http://example.com
 *
 *
 * Sec-WebSocket-Key 是一個 Base64 encode 的值

 * Sec_WebSocket-Protocol 是一個用戶定義的字符串，用來區分同 URL 下，不同的服務所需要的協議。簡單理解：今晚我要服務A
Sec-WebSocket-Version 是告訴伺服器所使用的 WebSocket Draft （協議版本）

原文網址：https://kknews.cc/code/pl638kp.html
原文網址：https://kknews.cc/code/pl638kp.html
 *
 *
 *
 *
 *  server response
 * HTTP/1.1 101 Switching Protocols
 * Upgrade: websocket
 * Connection: Upgrade
 * Sec-WebSocket-Accept: HSmrc0sMlYUkAGmm5OPpG2HaGWk=
 * Sec-WebSocket-Protocol: chat

原文網址：https://kknews.cc/code/pl638kp.html
 *
 */
class Sock
{

    public $sockets;
    public $users;
    public $master;

    public $host;
    public $port;
#http://inspiregate.com/programming/php/127-php-socket-teaching.html
    public function __construct($host = '127.0.0.1', $port = 3333)
    {
        $this->host = $host;
        $this->port = $port;
        // 如果你嘗試使用Web瀏覽器來運行這個腳本，那麼很有可能它會超過30秒的限時。你可以使用下面的代碼來設置一個無限的運行時間，但是還是建議使用命令提示符來運行。
        set_time_limit(0);
        $this->master = $this->WebSocket($host, $port);
        $this->sockets = array( $this->master);
        $this->users = [];
    }

    /*
    ——————————-
    *    @socket通訊整個過程
    ——————————-
    *    @socket_create
    *    @socket_bind
    *    @socket_listen
    *    @socket_accept
    *    @socket_read
    *    @socket_write
    *    @socket_close
    ——————————–
    */


    function run()
    {

        $count  = 0;
        //循环，让服务器无限获取客户端传过来的信息
        while (true) {

            $this->log("循環次數" . ++$count, __LINE__);
            // create a copy, so $this->sockets doesn't get modified by socket_select()
            $watchSocketRead = $this->sockets;
            //作用：獲取read數組中活動的socket，並且把不活躍的從read數組中刪除,具體的看文檔

            $write  = NULL;
            $exception = NULL;

             // 看client有沒有寫東西進來

            $this->log('socket_selecting', __LINE__);
            $this->log($this->sockets, __LINE__);
            $selectStatus = socket_select($watchSocketRead, $write, $exception, NULL);
            $this->log($watchSocketRead, __LINE__);
            $this->log('socket_selecting end', __LINE__);

            if ($selectStatus === false) {
                $this->log($exception, __LINE__);
                exit;
            } else if ($selectStatus <= 0) {
                $this->log('socket listen = zero', __LINE__);
                continue;
            }
            $this->log('=========================================', __LINE__);
            $this->log($this->master, __LINE__);
            $this->log($watchSocketRead, __LINE__);
            if (in_array($this->master, $watchSocketRead)) {
                $this->log('================handshak=========================', __LINE__);
                if (($newSocket = socket_accept($this->master)) < 0) {
                    $this->log("socket_accept() failed: reason: " . socket_strerror($newSocket) . "\n", __LINE__);
                }
                if ($newSocket === false) {
                    $this->log("socket_accept() failed: reason: return false " . socket_strerror($newSocket) . "\n", __LINE__);
                    continue;
                }

                $this->log($newSocket, __LINE__);

                $header = socket_read($newSocket, 1024);



                $this->handshaking($header, $newSocket, $this->host, $this->port);

                $this->sockets[] = $newSocket;
                $this->users[] = array(
                    'socket' => $newSocket,
                    'shou' => true
                );

                //获取client ip 编码json数据,并发送通知
                socket_getpeername($newSocket, $ip);
                $response = $this->mask(json_encode(array('type' => 'system', 'message' => $ip . ' connected')));


                $found_socket = array_search($this->master, $watchSocketRead);

                unset($watchSocketRead[$found_socket]);





                $this->send_message($response);


            }

            // check if there is a client trying to connect
            foreach ($watchSocketRead as $sock) {
                /**
                 *
                 * 1.socket_recv支持多种flag，用于不同场景
                     2.socket_recv可以检测socket关闭的情况(例如对端关闭了socket)
                    returns the number of bytes received, or FALSE if there was an error.
                    */
                // read client input
                //从已连接的socket接收数据
                $this->log($sock, __LINE__);
                if (false !== ($bytes = socket_recv($sock, $buffer, 2048, 0))) {
                    $this->log("Read $bytes bytes from socket_recv()", __LINE__);
                } elseif ($bytes === 0)  {
                    # client close
                    $this->log('WS_READ_ERR2: '.socket_strerror(socket_last_error($sock)), __LINE__);

                } else {

                    $this->log("socket_recv() failed; reason: " . socket_strerror(socket_last_error($sock)) . "\n", __LINE__);

                }

                $received_text = $this->unmask($buffer);

                $this->log('收到:' . $received_text, __LINE__);

                $k = $this->getuserbysocket($sock);

                $this->log(__LINE__ . '第' . $k . '個socket' . "\n", __LINE__);


                if ($bytes < 9) {


                    $this->close($sock);


                    $found_socket = array_search($sock, $watchSocketRead);
                    unset($watchSocketRead[$found_socket]);

                    $ar['remove'] = true;
                    $ar['removekey'] = $k;
                    $ar['nrong'] =   '退出聊天室';
                    $response_text = $this->mask(json_encode($ar));
                    $this->send_message($response_text);
                    continue;
                }



                // 有沒有握手

                parse_str($received_text, $g);


                if ($g["type"] == "ltiao") {

                    # 開始聊天
                    $ar['name'] = $this->users[$k]["ming"];
                    $ar['nrong'] = $g['nr'];
                    $ar['users'] = $this->getusers();
                    $ar['time'] = 'time';
                } else {
                    # 初次近來
                    $this->users[$k]['ming'] = $g['ming'];
                    $ar['add'] = true;
                    $ar['nrong'] = '欢迎' . $g['ming'] . '加入！';
                    $ar['users'] = $this->getusers();
                    $ar['type'] = $g['type'];
                    $ar['name'] = $g['ming'];
                }


                $response_text = $this->mask(json_encode($ar));
                    $this->send_message($response_text);

                    $ar = [];

            }

        }
    }


    function close($sock)
    {
        $k = array_search($sock, $this->sockets);
        socket_close($sock);
        unset($this->sockets[$k]);
        unset($this->users[$k]);
        $this->log("key:$k close", __LINE__);
    }



    function getuserbysocket($sock)
    {
        foreach ($this->users as $k => $v) {
            if ($sock == $v['socket'])
                return $k;
        }
        return false;
    }
    //解码数据
    function unmask($text)
    {
        $length = ord($text[1]) & 127;
        if ($length == 126) {
            $masks = substr($text, 4, 4);
            $data = substr($text, 8);
        } elseif ($length == 127) {
            $masks = substr($text, 10, 4);
            $data = substr($text, 14);
        } else {
            $masks = substr($text, 2, 4);
            $data = substr($text, 6);
        }
        $text = "";
        for ($i = 0; $i < strlen($data); ++$i) {
            $text .= $data[$i] ^ $masks[$i % 4];
        }
        return $text;
    }



    //编码数据
    function mask($text)
    {
        $b1 = 0x80 | (0x1 & 0x0f);
        $length = strlen($text);

        if ($length <= 125)
            $header = pack('CC', $b1, $length);
        elseif ($length > 125 && $length < 65536)
            $header = pack('CCn', $b1, 126, $length);
        elseif ($length >= 65536)
            $header = pack('CCNN', $b1, 127, $length);
        return $header . $text;
    }
    # http://inspiregate.com/programming/php/127-php-socket-teaching.html
    function WebSocket($address, $port)
    {
        # socket_create()函數運行成功返回一個 包含socket的資源類型
        if (($socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) < 0) {
            $this->log("socket_create() 失敗的原因是:" . socket_strerror($socket), __LINE__);
        }
        # 設置socket選項
        socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
        # 把socket綁定在一個IP地址和端口上
        if (($return = socket_bind($socket, $address, $port)) < 0) {
            $this->log("socket_bind() 失敗的原因是:" . socket_strerror($return), __LINE__);
        }
        // 第二個參數 最多10个人连接，超过的客户端连接会返回WSAECONNREFUSED错误
        // socket_listen ( resource $socket [, int $backlog = 0 ] )
        // backlog	设置排队等候的连接队列最大值，如果连接的时候，连接队列已达最大值，则客户端会收到一个ECONNREFUSED标识错误
        # 監聽由指定socket的所有連接
        if (($return = socket_listen($socket)) < 0) {
            $this->log("socket_listen() 失敗的原因是:" . socket_strerror($return), __LINE__);
        }


        # 獲取本地socket的ip地址
        socket_getsockname($socket, $addr, $port);
        $this->log("服务端ip:" . ($addr), __LINE__ );
        $this->log("服务端端口:" . ($port), __LINE__ );


        //If you want to have multiple clients on a socket you will have to use non blocking.
        #设置非阻塞。一个很关键的方法，如果没设置非阻塞，socket 的操作就会被阻塞，例如 receive, send, connect, accept 等等。默认情况下
        #https://learnku.com/articles/9433/php-socket-communication-tcp
         socket_set_nonblock($socket);


        $this->log('socket Started : ' . date('Y-m-d H:i:s'), __LINE__);
        $this->log('Listening on : ' . $address . ' port ' . $port, __LINE__);
        return $socket;
    }




    //握手的逻辑
    function handshaking($receved_header, $client_conn, $host, $port)
    {
        $headers = array();
        $lines = preg_split("/\r\n/", $receved_header);
        foreach ($lines as $line) {
            $line = chop($line);
            if (preg_match('/\A(\S+): (.*)\z/', $line, $matches)) {
                $headers[$matches[1]] = $matches[2];
            }
        }

        $secKey = $headers['Sec-WebSocket-Key'];
        $secAccept = base64_encode(pack('H*', sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
        $upgrade = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
            "Upgrade: websocket\r\n" .
            "Connection: Upgrade\r\n" .
            "WebSocket-Origin: $host\r\n" .
            "WebSocket-Location: ws://$host:$port/Sock.php\r\n" .
            "Sec-WebSocket-Accept:$secAccept\r\n\r\n";
        socket_write($client_conn, $upgrade, strlen($upgrade));
    }



    function send_message($msg)
    {
        $this->log($this->sockets, __LINE__);
        foreach ($this->sockets as $changed_socket) {
            if ($changed_socket == $this->master) continue;
            $res = socket_write($changed_socket, $msg, strlen($msg));
            if (!$res) {
                $err_msg = socket_strerror(socket_last_error($changed_socket));
                socket_close($changed_socket);
                throw new \Exception("socket_write() failed:" . $err_msg);
            }
        }
        return true;
    }



    function getusers()
    {
        $ar = [];
        foreach ($this->users as $k => $v) {
            $ar[$k] = $v['ming'];
        }
        return $ar;
    }




    function log($str, $line = __LINE__)
    {
        echo 'line: ' . $line . ' ';
        var_dump($str);
        echo PHP_EOL;
    }
}
