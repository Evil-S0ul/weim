<?php
/**
 * Created by PhpStorm.
 * User: ttt
 * Date: 2017/6/2
 * Time: 11:00
 */

$ip = '0.0.0.0';
$port = 8001;

$ws = new swoole_websocket_server($ip, $port);

//设置server运行时的各项参数
//$ws->set(array(
//    'daemonize' => true, //是否作为守护进程
//));

file_put_contents('runtime/user_list.json', '{}');


$ws->on('open', function(swoole_websocket_server $ws, swoole_http_request $request){

    echo "server: handshake success with fd{$request->fd}\n";

    saveUser($request->fd, $request->get['username']);

    $arr = [
        'msg'=>"Hello ".$request->get['username'],
        'username'=>'小娜'
    ];
    $ws->push($request->fd, json_encode($arr));
});

$ws->on('message', function(swoole_websocket_server $ws, swoole_websocket_frame $frame){
    echo "receive from {$frame->fd}:{$frame->data}, opcode:{$frame->opcode}, fin:{$frame->finish}\n";
    //$ws->push($frame->fd, $frame->data);    //原样返回

    $list = userList();
    foreach($list as $v){
        if($v['fd'] == $frame->fd){
            continue;
        }
        $res = dealMsg($frame->fd, $frame->data);
        $ws->push($v['fd'], json_encode($res));
    }
});

$ws->on('close', function(swoole_websocket_server $ws, $fd){
    echo "client {$fd} closed\n";
});

echo "Swoole Websocket Server listening on $ip:$port".PHP_EOL;
echo 'Waiting for client to connect...';
$ws->start();

function dealMsg($fd, $data){
    $username = getUsernameByFd($fd);
    return [
        'username'=>$username,
        'msg'=>$data
    ];
}

function getUsernameByFd($fd){
    $list = userList();
    return isset($list['fd_'.$fd]) ? $list['fd_'.$fd]['username'] : '';
}

function userList(){
    return json_decode(file_get_contents('runtime/user_list.json'), true);
}

function saveUser($fd, $username){
    $list = userList();
    $user = isset($list['fd_'.$fd]) ? $list['fd_'.$fd] : [];
    $user['username'] = $username;
    $user['fd'] = $fd;
    $list['fd_'.$fd] = $user;
    file_put_contents('runtime/user_list.json', json_encode($list));
}
