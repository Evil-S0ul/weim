<?php
/**
 * Created by PhpStorm.
 * User: ttt
 * Date: 2017/6/2
 * Time: 10:08
 */

session_start();

//login
if (!empty($_POST['username'])) {
    $_SESSION['username'] = $_POST['username'];
}

// check login
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
if (empty($username)) {
    echo '<form action="" method="post">
username:<input type="text" name="username" value="" required>
<button type="submit">Login</button>
</form>';
    exit;
}

$config = require '../config/app.php';
$server_url = $config['server_url'];

?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>WeiIM</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        ul, ol li {
            list-style: none;
        }

        button {
            padding: 2px 5px;
            float: left;
            margin-right: 5px;
        }

        .msg {
            border-bottom: 1px dashed #ccc;
            padding: 10px 5px 5px 5px;
        }

        .sys_msg {
            text-align: left;
        }

        .my_msg {
            text-align: right;
        }

        #box {
            margin: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            width: 500px;
            height: 300px;
            overflow-y: auto;
        }

        #panel {
            margin: 10px;
        }

        #myTextArea{
            border:1px solid #ccc;
            height:100px;
            width: 510px;
            padding: 5px;
            overflow-y:auto;
            overflow-x: inherit;
        }
        #myTextArea:active{
            border:1px solid blue;
        }
    </style>
</head>
<body>
<div id="box">
    <ul>
        <li class="sys_msg msg">小娜：你好<?= $username ?>，欢迎来到WeiIM聊天室~</li>
        <!--<li class="my_msg msg">我：你好</li>-->
    </ul>
</div>
<div id="panel">
    <p>
<!--        <textarea name="content" id="content" style="width: 510px;height: 100px;padding: 5px;"></textarea>-->
        <div id="myTextArea" contenteditable="true" style="margin-top: 5px;margin-bottom: 5px;"></div>
    </p>
    <p style="width: 522px;overflow: hidden;">
        <button type="button" onclick="connect();">重新连接</button>
        <button type="button" onclick="disconnect();">断开连接</button>
        <button type="button" onclick="get_state();">查看连接状态</button>
        <button type="button" onclick="send_msg();" style="float: right;margin-right: 0">发送</button>
    </p>
</div>


<script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.js"></script>
<script>

    $(function(){
        connect();
    });

    function send_msg() {
        var content = $("#myTextArea").html();
        console.log(content);
        if(('readyState' in ws) && ws.readyState == 1){
            if (content.length > 0) {
                log_my_msg(content);
                ws.send(content);
                $("#myTextArea").html('');
            }
        } else {
            log_sys_msg('还未建立连接');
        }
    }

    var ws = {};

    function log_sys_msg(msg, username) {
        if(typeof(username)=="undefined"){
            username = '小娜';
        }
        var tpl = '<li class="sys_msg msg">'+username+'：' + msg + '</li>';
        $("#box ul").append(tpl);
        scroll_ctl();
    }

    function log_my_msg(msg) {
        var tpl = '<li class="my_msg msg">我：' + msg + '</li>';
        $("#box ul").append(tpl);
        scroll_ctl();
    }

    function scroll_ctl() {
        $('#box').scrollTop($('#box')[0].scrollHeight);
    }

    function connect() {
        if(('readyState' in ws) && ws.readyState == 1){
            log_sys_msg("您已经与服务器建立了连接 当前连接状态：" + ws.readyState);
            return false;
        }
        try {
            ws = new WebSocket("<?=$server_url?>?username=<?=$username?>");//连接服务器
            ws.onopen = function (event) {
                log_sys_msg("已经与服务器建立了连接 当前连接状态：" + this.readyState);
            };
            ws.onmessage = function (event) {
                //log_sys_msg("接收到服务器发送的数据：" + event.data);
                var obj = eval("("+event.data+")");
                log_sys_msg(obj.msg, obj.username);
            };
            ws.onclose = function (event) {
                log_sys_msg("已经与服务器断开连接 当前连接状态：" + this.readyState);
            };
            ws.onerror = function (event) {
                log_sys_msg("WebSocket异常！");
            };
        } catch (ex) {
            log_sys_msg(ex.message);
        }
    }

    function disconnect() {
        ws.close();
    }

    function get_state() {
        if ('readyState' in ws) {
            log_sys_msg('当前连接状态：' + ws.readyState);
        } else {
            log_sys_msg('还未建立连接');
        }
    }
</script>
</body>
</html>