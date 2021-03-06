<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>WeIM - 登录</title>
    <meta name="keywords" content="">
    <meta name="description" content="">

    <link rel="shortcut icon" href="<?=asset('favicon.ico')?>">

    <link href="<?=asset('plugins/bootstrap/3.3.7/css/bootstrap.min.css')?>" rel="stylesheet">
    <link href="<?=asset('plugins/font-awesome/4.7.0/css/font-awesome.min.css')?>" rel="stylesheet">

    <link href="<?=asset('assets/css/login.css')?>" rel="stylesheet">
    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html"/><![endif]-->
    <script>if (window.top !== window.self) {window.top.location = window.location;}</script>
    <style>

    </style>
</head>

<body class="gray-bg">

<div class="middle-box text-center loginscreen  animated fadeInDown">
    <div>
        <div>

            <h1 class="logo-name">We</h1>

        </div>
        <h3>欢迎使用 WeIM</h3>

        <form class="m-t" role="form" action="" id="loginForm">
            <div class="form-group">
                <input type="email" name="email" class="form-control" placeholder="电子邮件地址" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="密码" required>
            </div>
            <button type="submit" class="btn btn-primary block full-width m-b">登 录</button>

            <p class="text-muted text-center">
                <a href="#">
                    <small>忘记密码了？</small>
                </a>
                |
                <a href="">注册一个新账号</a>
            </p>

        </form>
    </div>
</div>
<script src="<?=asset('plugins/jquery/2.2.1/jquery.min.js')?>"></script>
<script src="<?=asset('plugins/bootstrap/3.3.7/js/bootstrap.min.js')?>"></script>
<script>
    $('#loginForm').submit(function(){
        var data = $(this).serialize();
        $.post('<?= url('login')?>', data, function(res){
            if(res.ret == 200){
                window.location.href = '<?= url('user')?>';
            }else{
                alert(res.msg);
            }
        }, 'json');
        return false;
    });
</script>
</body>
</html>
