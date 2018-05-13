<?php
//session_start();
$price=$_REQUEST["price"];
//var_dump($price);
//-------------------------------------------db_connect_start--------------------------------------
$mysql_server_name = 'localhost';
$mysql_username = 'root';
$mysql_password = 'Xinxiuda123';
$mysql_database = 'WeixinPay';
$conn = mysql_connect($mysql_server_name, $mysql_username, $mysql_password) or die("error connecting"); //连接数据库
mysql_select_db($mysql_database); //打开数据库
//-------------------------------------------db_connect_end--------------------------------------
//微信扫码支付
$order_no = date("YmdHis") . rand(1000, 9999); //支付订单号

$order_money = $price; //订单金额 元
$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER["REQUEST_URI"];
//$url_notify = $url . "notify.php"; //微信回调地址
$url_notify = "http://www.cunzaitech.com/linkall/dbpay/notify.php";
//添加订单
//$query = mysql_query("INSERT INTO `order` (`order_no`,`order_money`,`pay_type`,`addtime`) VALUES ('" . $order_no . "', '" . $order_money . "','weixin', '" . time() . "');");

//var_dump(mysql_fetch_array($query));
include_once "weixinpay/lib/WxPay.Api.php";
include_once "weixinpay/example/WxPay.NativePay.php";

$NativePay = new NativePay();
$title = "微信扫码支付";

$input = new WxPayUnifiedOrder();
$input->SetBody($title);   //商品描述
$input->SetAttach(""); //附加数据
$input->SetOut_trade_no($order_no);
$input->SetTotal_fee($order_money * 100); // 总金额
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 6000));
$input->SetGoods_tag(""); //商品标记，代金券或立减优惠功能的参数，说明详见
$input->SetNotify_url($url_notify);
$input->SetTrade_type("NATIVE");
$input->SetProduct_id($order_no); //商品ID 或订单编号
$result = $NativePay->GetPayUrl($input);


$code_url = urlencode($result["code_url"]);
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport"
          content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no"/>
    <title>微信扫码支付</title>
    <style>
        body {
            background: #f5f5f5;
        }

        .wx_img {
            margin-top: 30px;
        }

        .wx_img img {
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>

<div align="center">
    <!--<h1 style="color:red;">PHP原生微信扫码支付</h1>-->
    <img src="images/WePayLogo.png" width="180px;" style="display:block; margin:0px auto; margin-top:100px;">
    <div class="wx_img"><img alt="微信扫码支付" src="weixinpay/example/qrcode.php?data=<?php echo $code_url; ?>"></div>
    <input type="hidden" value="<?php echo $order_no; ?>" id="order_no"/>
</div>
<style>
    button {
        background-color: #7fbf4d;
        background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #7fbf4d), color-stop(100%, #63a62f));
        background-image: -webkit-linear-gradient(top, #7fbf4d, #63a62f);
        background-image: -moz-linear-gradient(top, #7fbf4d, #63a62f);
        background-image: -ms-linear-gradient(top, #7fbf4d, #63a62f);
        background-image: -o-linear-gradient(top, #7fbf4d, #63a62f);
        background-image: linear-gradient(top, #7fbf4d, #63a62f);
        border: 1px solid #63a62f;
        border-bottom: 1px solid #5b992b;
        border-radius: 3px;
        -webkit-box-shadow: inset 0 1px 0 0 #96ca6d;
        box-shadow: inset 0 1px 0 0 #96ca6d;
        color: #fff;
        font: bold 11px/1 "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Geneva, Verdana, sans-serif;
        padding: 7px 0 8px 0;
        text-align: center;
        text-shadow: 0 -1px 0 #4c9021;
        width: 150px;
    }
</style>
</body>
</html>
<script src="js/jquery.js" type="text/javascript"></script>
<script>
    changeOrderStatues();//检测订单是否支付成功
    function changeOrderStatues() {
        var order_no = $("#order_no").val();

        $.post("check_order.php", {order_no: order_no}, function (data) {
            if (data > 0) {
                //订单返回值大于0表示支付成功
                /*alert("感谢您赞助，付款成功");*/
                location.href = "order_detail.php?order_no=" + order_no + ""; //支付成功后跳转到订单详情页
            }
        })
        setTimeout("changeOrderStatues()", 3000);
    }
</script>