<?php
//header('Content-Type:text/plain;charset=utf-8');
header('Content-Type:text/html;charset=utf-8');
session_start();
//-------------------------------------------db_connect_start--------------------------------------


$mysql_server_name='localhost';
$mysql_username='root';
$mysql_password='Xinxiuda123';
$mysql_database='linkall';  
$conn=mysql_connect($mysql_server_name,$mysql_username,$mysql_password) or die("error connecting") ; //连接数据库
mysql_select_db($mysql_database); //打开数据库
//-------------------------------------------db_connect_end--------------------------------------
  

 
/****
查询order表，订单是否支付成功。如果state=1,说明支付成功，调用money_send中的send.php进行转账功能
***/
$order_no=$_SESSION['THREE'];
$moneyPay=$_SESSION['paymoney'];
$deviceId=$_SESSION['six'];
var_dump($deviceId);

/*sql查询语句*/
$sql3="SELECT * FROM  `order` WHERE state=1 AND order_no='$order_no'";
    $result_money = mysql_query($sql3);
    //var_dump(mysql_num_rows($result_money));
    if(mysql_num_rows($result_money)==NULL){
        echo "支付失败";        
    }else{
        //echo '支付成功'; 
        //header("Location: ../../../money_send/send.php");
        $url = "http://www.cunzaitech.com/public/money_send/send.php?moneyPay=".$moneyPay."&deviceId=".$deviceId;  
        echo "<script language=\"javascript\" type=\"text/javascript\">";  
        echo "window.location.href='$url'";  
        echo "</script>";
    }
unset($_SESSION['THREE']);
unset($_SESSION['paymoney']);
unset($_SESSION['six']);

    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
?>