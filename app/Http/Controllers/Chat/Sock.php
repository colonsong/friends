<?php

namespace App\Http\Controllers\Chat;

class Sock {

    public $sockets;
    public $users;
    public $master;

    public function __construct($address = '127.0.0.1', $port = 3333){
        $this->master=$this->WebSocket($address, $port);
        $this->sockets = array($this->master);
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
    function run(){

        while(true) {

            $read = $this->sockets;
            //作用：獲取read數組中活動的socket，並且把不活躍的從read數組中刪除,具體的看文檔
            
            echo __LINE__ . "\n";
            $write = NULL;
            $except = NULL;
            /**1 阻塞程序继续往下执行和自动选择当前有活动的连接 */
            if ( socket_select($read,$write,$except,NULL) < 1) {
                echo __LINE__ . "\n";
                continue;
            }
            echo __LINE__ . "\n";
            var_dump($read);
        

            foreach($read as $sock){

                

                if($sock == $this->master){

                    if ( ($newSocket = socket_accept($this->master)) < 0) {
                        echo "socket_accept() failed: reason: ". socket_strerror($newSocket) . "\n";
                        
                    }
                    //$key=uniqid();
                    $this->sockets[] = $newSocket;
                    $this->users[] = array(
                        'socket' => $newSocket,
                        'shou' => false
                    );

                    echo __LINE__ . "\n";
                    var_dump($read);

                }else{

                    echo __LINE__ . "\n";
                    
                    /**
                     * 
                     * 1.socket_recv支持多种flag，用于不同场景
                         2.socket_recv可以检测socket关闭的情况(例如对端关闭了socket)
                        returns the number of bytes received, or FALSE if there was an error.
                    */
                    if (false !== ($bytes = socket_recv($sock, $buffer, 2048, 0))) {
                        echo "Read $bytes bytes from socket_recv() \n";
                    } else {
                        echo "socket_recv() failed; reason: " . socket_strerror(socket_last_error($sock)) . "\n";
                    }

        
                    
                    $k = $this->getuserbysocket($sock);

                    echo __LINE__ . '第' . $k . '個socket'. "\n";
                    

                    if ( $bytes < 9 ){
                        echo '@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@';
                        $name = $this->users[$k]['ming'];
                        $this->close($sock);
                        $this->send2($name,$k);
                        continue;
                    }

                    var_dump($this->users);
                    // 有沒有握手
                    if (!$this->users[$k]['shou']) {
                        echo __LINE__ . "\n";
                        $this->woshou($k,$buffer);
                    } else {
                        echo __LINE__ . "\n";
                        $buffer = $this->uncode($buffer);
                        var_dump($buffer);
                        $this->send($k,$buffer);
                    }
                }
            }
        }
    }
    function close($sock){
        $k=array_search($sock, $this->sockets);
        socket_close($sock);
        unset($this->sockets[$k]);
        unset($this->users[$k]);
        $this->e("key:$k close");
    }



    function getuserbysocket($sock){
        foreach ($this->users as $k=>$v){
        if ( $sock==$v['socket'])
            return $k;
        }
        return false;
    }


    function WebSocket($address,$port){
        if (($server = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) < 0) {
            echo "socket_create() 失敗的原因是:".socket_strerror($server)."\n";
        }
        socket_set_option($server, SOL_SOCKET, SO_REUSEADDR, 1);

        if ( ($ret = socket_bind($server, $address, $port)) < 0) {
            echo "socket_bind() 失敗的原因是:".socket_strerror($ret)."\n";
        }
        // 第二個參數 最多10个人连接，超过的客户端连接会返回WSAECONNREFUSED错误

        if ( ($ret = socket_listen($server)) < 0) {
            echo "socket_listen() 失敗的原因是:".socket_strerror($ret)."\n";

        }
        $this->e('Server Started : '.date('Y-m-d H:i:s'));
        $this->e('Listening on : '.$address.' port '.$port);
        return $server;
    }

    function woshou($k,$buffer){

        echo "握手..\n\n";
        $buf = substr($buffer,strpos($buffer,'Sec-WebSocket-Key:')+18);
        $key = trim(substr($buf,0,strpos($buf,"\r\n")));
        $new_key = base64_encode(sha1($key."258EAFA5-E914-47DA-95CA-C5AB0DC85B11",true));
        $new_message = "HTTP/1.1 101 Switching Protocols\r\n";
        $new_message .= "Upgrade: websocket\r\n";
        $new_message .= "Sec-WebSocket-Version: 13\r\n";
        $new_message .= "Connection: Upgrade\r\n";
        $new_message .= "Sec-WebSocket-Accept: " . $new_key . "\r\n\r\n";
        socket_write($this->users[$k]['socket'],$new_message,strlen($new_message));
        $this->users[$k]['shou']=true;

        var_dump($this->users);
        return true;
    }



    function uncode($str){
        $mask = []; 
        $data = ''; 
        $msg = unpack('H*',$str); 
        $head = substr($msg[1],0,2); 

        if (hexdec($head{1}) === 8) { 
            $data = false; 
        }else if (hexdec($head{1}) === 1){ 
            $mask[] = hexdec(substr($msg[1],4,2)); 
            $mask[] = hexdec(substr($msg[1],6,2)); 
            $mask[] = hexdec(substr($msg[1],8,2)); 
            $mask[] = hexdec(substr($msg[1],10,2)); 
            $s = 12; 
            $e = strlen($msg[1])-2; 
            $n = 0; 
            for ($i=$s; $i<= $e; $i+= 2) { 
                $data .= chr($mask[$n%4]^hexdec(substr($msg[1],$i,2)));
                $n++; 
            } 
        } 
        return $data;
    }



    function code($msg){
        $msg = preg_replace(array('/\r$/','/\n$/','/\r\n$/',), '', $msg);
        $frame = []; 
        $frame[0] = '81'; 
        $len = strlen($msg); 
        $frame[1] = $len<16?'0'.dechex($len):dechex($len); 
        $frame[2] = $this->ord_hex($msg); 
        $data = implode('',$frame); 
        return pack("H*", $data); 
    }



    function ord_hex($data) { 
        $msg = ''; 
        $l = strlen($data); 
        for ($i= 0; $i<$l; $i++) { 
        $msg .= dechex(ord($data{$i})); 
        } 
        return $msg; 
    }


    function send($k,$msg){
        /*$this->send1($k,$this->code($msg),'all');*/
        //  "type=add&ming=AAAA"
        parse_str($msg,$g);
    
        $ar = [];

        if (empty($g['type'])) {
            return;
        }
        if($g['type']=='add'){

            echo __LINE__ . "\n";
            $this->users[$k]['ming']=$g['ming'];
            $ar['add']=true;
            $ar['nrong']='欢迎'.$g['ming'].'加入！';
            $ar['users']=$this->getusers();
            $ar['type'] = $g['type'];
            $ar['name'] = $g['ming'];
           
            $key='all';
        }else if($g['type']=='ltiao'){
            echo __LINE__ . "\n";

            var_dump($this->users[$k]);
            $ar['name']=$this->users[$k]["ming"];
            $ar['nrong']=$g['nr'];
            $ar['users']=$this->getusers();
            $ar['time'] = 'time';
            $key=$g['key'];
        }
        $msg=json_encode($ar);
      
        $msg = $this->code($msg);
        $this->send1($k,$msg);
        //socket_write($this->users[$k]['socket'],$msg,strlen($msg));
    }



    function getusers(){
        $ar=[];
        foreach($this->users as $k=>$v){
            $ar[$k]=$v['ming'];
        }
        return $ar;
    }



    function send1($k,$str,$key='all'){
        echo __LINE__ . "\n";
        if($key=='all'){
            echo __LINE__ . "\n";
            var_dump($str);
            foreach($this->users as $v){
                socket_write($v['socket'],$str,strlen($str));
            }
        }else{
            echo __LINE__ . "\n";
            var_dump($key);

            if($k!=$key) {
                socket_write($this->users[$k]['socket'],$str,strlen($str));
            }
            socket_write($this->users[$key]['socket'],$str,strlen($str));
        }
    }



    function send2($ming,$k){
     $ar['remove']=true;
     $ar['removekey']=$k;
     $ar['nrong']=$ming.'退出聊天室';
     $str = $this->code(json_encode($ar));
     $this->send1(false,$str,'all');
    }



    function e($str){
     
     echo $str . "\n";
    }
   }