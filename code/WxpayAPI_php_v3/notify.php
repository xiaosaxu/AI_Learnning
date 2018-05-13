<?php

//-------------------------------------------db_connect_start--------------------------------------
$mysql_server_name='localhost';
$mysql_username='root'; 
$mysql_password='Xinxiuda123';
$mysql_database='WeixinPay'; 
$conn=mysql_connect($mysql_server_name,$mysql_username,$mysql_password) or die("error connecting") ; //连接数据库
mysql_select_db($mysql_database); //打开数据库
//-------------------------------------------db_connect_end--------------------------------------

$simple = json_decode(json_encode(simplexml_load_string($GLOBALS['HTTP_RAW_POST_DATA'], 'SimpleXMLElement', LIBXML_NOCDATA)), true);
$order_no = $simple['out_trade_no'];//商户订单号
$third_id = $simple['transaction_id'];//微信流水号
$pay_money = $simple['total_fee'];//实际支付金额
if ($order_no) {//若是支付成功 ，订单状态state=1表示已支付成功
    $query = mysql_query("UPDATE `order` SET `state` = '1',update_time='" . time() . "',trade_no='".$third_id."' WHERE `order_no` ='" . $order_no . "'");
}


  file_put_contents('notify.txt', json_encode($simple));