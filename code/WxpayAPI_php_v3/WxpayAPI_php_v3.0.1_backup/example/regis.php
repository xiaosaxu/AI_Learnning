<?php
    //-------------------------------------------db_connect_start--------------------------------------\

session_start();
//require_once "h5pay_modified.php";   
$mysql_server_name='localhost';
$mysql_username='root';
$mysql_password='Xinxiuda123';
$mysql_database='linkall';  
$conn=mysql_connect($mysql_server_name,$mysql_username,$mysql_password) or die("error connecting") ; //连接数据库
mysql_select_db($mysql_database); //打开数据库
//$equipment_id=$_GET['m'];
//$openId=$_GET['openId'];
$one = $_SESSION['one'];
$two = $_SESSION['two'];
echo $one;
echo $two;
//echo $equipment_id;
///echo $openId;
  $sql="insert into public(equipment_id,openid) values('$one','$two')";
           
  mysql_query($sql);

   
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
    
</head>
<body>   
<main class="auth-main">
    <div class="auth-block">
        
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