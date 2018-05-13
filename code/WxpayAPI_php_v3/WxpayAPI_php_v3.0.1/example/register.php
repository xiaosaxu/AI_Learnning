<?php
session_start();
header( 'Content-Type:text/html;charset=utf-8');
include_once 'conn.php';
$ndate =date("Y-m-d");
if (isset($_POST['submit']))
{
    $mima=$_POST["mima"];                   //密码
    $dianhua=$_POST["dianhua"];             //电话
    $yzm=$_POST["yzm"];
    $sql2="select * from user_info where phone='$dianhua'";
    //var_dump($sql2);exit;
    $result = mysql_query($sql2);    
    //var_dump(mysql_error($result));
    //var_dump($result);exit;
    if(mysql_num_rows($result)!=0) {
        echo "<script>javascript:alert('手机号已被注册');history.back()</script>";
    }else {
        $sql3="select * from yanzhengma where yzm=$yzm";
        mysql_query($sql3);
        /*判断验证码是否正确*/
        if($_SESSION["yzm"]=$yzm){
            $sql="insert into user_info(password,phone) values('$mima','$dianhua') ";
            mysql_query($sql);
            echo "<script>javascript:alert('注册成功!');location.href='index.php';</script>";
        }else{
            echo "<script>alert('验证码错误'); history.go(-1);</script>";
            exit;
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../Assets/css/login.css">
    <link rel="stylesheet" href="../Assets/css/bootstrap.min.css">
    <script src="../Assets/js/jquery.min.js"></script>
    <title>注册</title>
    <script type="text/javascript">
        $(function(){
            $(".send").click(function(){
                var dianhua=$("input[name=dianhua]").val();
                $.ajax({
                    url:"regcheck.php",
                    type:"post",
                    data:{dianhua},
                    success:function (e) {
                        if(e){
                            $(".send").html("发送成功");
                        }else{
                            $(".send").html("发送失败");
                        }
                    }
                })

            })
        })
    </script>
</head>
<body>
<main class="auth-main">
    <div class="auth-block">
        <h1>云管理系统</h1>
        <a href="login.php" class="auth-link">立即登录</a>
        <form action="register.php" method="post">
        <div class="form-horizontal">
            <div class="form-group">
                <label class="col-sm-2 control-label">手机号：</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control phone" value="" name="dianhua">
                    <a href="javascript:;" class="send">发送</a>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">密码：</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" value="" name="mima">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">验证码：</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" value="" name="yzm">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-10 col-sm-offset-2">
                    <button class="btn btn-default btn-auth" name="submit">设备id绑定成功</button>
                </div>
            </div>
        </div>  
        </form>
    </div>
    <div class="website">
        <a href="/linkall/index.php" target="_blank">官方网站</a>
    </div>
</main>
</body>
</html>