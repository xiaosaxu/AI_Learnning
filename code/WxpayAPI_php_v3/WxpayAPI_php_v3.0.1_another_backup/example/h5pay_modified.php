 <?php
session_start();
//-------------------------------------------db_connect_start--------------------------------------

$mysql_server_name='localhost';
$mysql_username='root';
$mysql_password='Xinxiuda123';
$mysql_database='linkall';  
$conn=mysql_connect($mysql_server_name,$mysql_username,$mysql_password) or die("error connecting") ; //连接数据库
mysql_select_db($mysql_database); //打开数据库
//-------------------------------------------db_connect_end--------------------------------------
  
//微信扫码支付
$order_no = date("YmdHis") . rand(1000, 9999); //支付订单号
var_dump($order_no);
$equipment_id=$_GET['equipment_id'];
// var_dump($equipment_id);
 //echo $equipment_id;
$money=$_GET['money'];
//var_dump($money);
//$money = 10000;
$order_money = $money; //订单金额 元
$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER["REQUEST_URI"];
//$url_notify = $url . "notify.php"; //微信回调地址
$url_notify = "http://www.cunzaitech.com/other/weixinpay/dbpay/notify.php";
//添加订单
$query = mysql_query("INSERT INTO `user` (`equipment_id`,`order_money`) VALUES ('" . $equipment_id . "','" . $money . "');");
$query = mysql_query("INSERT INTO `order` (`order_no`,`order_money`,`pay_type`,`addtime`,`equipment_id`) VALUES ('" . $order_no . "', '" . $money . "','weixin', '" . time() . "','" . $equipment_id . "');");


 //首先:   
//1.商户要开通H5支付
//2.设置好您需要H5支付的使用域名.微信官方设置网址为https://pay.weixin.qq.com/index.php/extend/pay_setting
//开始配置=========
 $sql2="select * from public where equipment_id='$equipment_id'";
    
    $result8 = mysql_query($sql2);    
    //echo mysql_num_rows($result8);
   if(mysql_num_rows($result8)==0) {
       echo "<script>location.href='regis.php';</script>";
   }
 //var_dump($money);  
//echo $money;
require_once "../lib/WxPay.Api.php";
require_once "WxPay.JsApiPay.php";
require_once 'log.php';

//初始化日志
$logHandler= new CLogFileHandler("../logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

//打印输出数组信息
function printf_info($data)
{
    foreach($data as $key=>$value){
        echo "<font color='#00ff55;'>$key</font> : $value <br/>";
    }
}

   
//①、获取用户openid
$tools = new JsApiPay();
$openId = $tools->GetOpenid();
//echo $openId;
//echo "haha";    
 $_SESSION['one']=$equipment_id;
 $_SESSION['two']=$openId; 
 
 
 
 $_SESSION['paymoney']=$money;                                      /*选择的金额*/
 $_SESSION['THREE']=$order_no;                                      /*设备发起支付时产生的订单号*/

 
 var_dump($_SESSION['paymoney']);
//②、统一下单
if($money>0){
$input = new WxPayUnifiedOrder();
$input->SetBody("test");
$input->SetAttach("test");
$input->SetOut_trade_no($order_no);
$input->SetTotal_fee($money); 
$input->SetTime_start(date("YmdHis")); 
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag("test");
$input->SetNotify_url($url_notify);
$input->SetTrade_type("JSAPI");
$input->SetOpenid($openId);
$order = WxPayApi::unifiedOrder($input);
//echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
//printf_info($order);
$jsApiParameters = $tools->GetJsApiParameters($order);
//var_dump($jsApiParameters);
require '../../../../onenet/restful_api/PHPSDK/OneNetApi.php';       

$apikey = 'QfI9JKZy9=nfq=ulp0KpAgBxPL8=';
$apiurl = 'http://api.heclouds.com';

//创建api对象
$sm = new OneNetApi($apikey, $apiurl);

//----------------获取设备信息-------------
$device_id = '27411782';
$device = $sm->device($device_id);
$error_code = 0;  
$error = '';  
if (empty($device)) {
    //处理错误信息
    $error_code = $sm->error_no();
    $error = $sm->error();
}
  
//echo "--------------------\n";
/*
//----------------创建数据流--------------
$datastream=array('id'=>'api_create_test');
$device = $sm->datastream_add($device_id,$datastream);
$error_code = 0;
$error = '';
if (empty($device)) {
    //处理错误信息
    $error_code = $sm->error_no();
    $error = $sm->error();
}
var_dump($device);
*/
//----------------添加数据点--------------
$uuid="api_create_test";
             
for($xunhuan=1,$nun_insert=0;$xunhuan<9;$xunhuan++)
{
    $nun_insert +=0.1;
    $datas_add =array(time()=>$nun_insert,time()+1=>$nun_insert,time()+2=>$nun_insert,time()+3=>$nun_insert);
    $device = $sm->datapoint_add($device_id,$uuid,$datas_add);
}
    $json = '{"a":1,"b":2,"c":3,"d":4,"e":5}'; 
    $de=$sm->send_data_to_mqtt_use_device_id($device_id,json_decode($json, true),json_decode($json, true));
    //echo 'a';
}
//获取共享收货地址js函数参数
//$editAddress = $tools->GetEditAddressParameters();

//③、在支持成功回调通知中处理成功之后的事宜，见 notify.php
/**
 * 注意：
 * 1、当你的回调地址不可访问的时候，回调通知会失败，可以通过查询订单来确认支付是否成功
 * 2、jsapi支付时需要填入用户openid，WxPay.JsApiPay.php中有获取openid流程 （文档可以参考微信公众平台“网页授权接口”，
 * 参考http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html）

 */
 
 
 
 
 
 
 

 
 
?>
 <!DOCTYPE html>   
 <html>
 <head>
	 <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	 <meta id="viewport" name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1; user-scalable=no;" />
	 <title>微信H5支付</title>
	 <style type="text/css">
		 html,body{height:100%;}
		 body{-webkit-user-select:none;-webkit-text-size-adjust:none;font-family:Helvetica;background:#ECECEC;}
		 body,p,ul,li,h1,h2,form,input{margin:0;padding:0;}
		 h1,h2{font-size:100%;}
		 ul{list-style:none;}
		 a,button,input,img{-webkit-touch-callout:none;outline:none;}
		 a{text-decoration:none;}
		 .hide{display:none!important;}
		 .clear:after{content:".";display:block;height:0;clear:both;visibility:hidden;}
		 a[class*="btn"]{display:block;height:42px;line-height:42px;color:#FFFFFF;text-align:center;border-radius:5px;}
		 .main{font-family:Helvetica;padding-bottom:10px;-webkit-user-select:none;}
		 .main h1{height:44px;line-height:44px;color:#FFFFFF;background:#3D87C3;text-align:center;font-size:20px;-webkit-box-sizing:border-box;box-sizing:border-box;}
		 .main h2{font-size:14px;color:#777777;margin:5px 0;text-align:center;}
		 .main .content{padding:10px 12px;}
		 .main .select li{position:relative;display:block;float:left;width:100%;margin-right:2%;height:150px;line-height:150px;text-align:center;border:1px solid #BBBBBB;color:#666666;font-size:16px;margin-bottom:5px;border-radius:3px;background-color:#FFFFFF;-webkit-box-sizing:border-box;box-sizing:border-box;overflow:hidden;}
		 .main .price{border-bottom:1px dashed #C9C9C9;padding:10px 10px 15px;margin-bottom:20px;color:#666666;font-size:12px;}
		 .main .price strong{font-weight:normal;color:#EE6209;font-size:26px;font-family:Helvetica;}
		 .main .btn-green{background-image:-webkit-gradient(linear, left top, left bottom, color-stop(0, #43C750), color-stop(1, #31AB40));border:1px solid #2E993C;box-shadow:0 1px 0 0 #69D273 inset;}
        .on{background-image:-webkit-gradient(linear, left top, left bottom, color-stop(0, #CCEED0), color-stop(1, #CCEED0));border:1px solid #2E993C;box-shadow:0 1px 0 0 #69D273 inset;}
        .on{display:block;height:42px;line-height:42px;color:#FFFFFF;text-align:center;border-radius:5px;}
	 </style>
 </head>
 <body>
 <article class="main">
	 <h1>H5微信支付</h1>
	 <section class="content">
		 <!--<h2>特价商品</h2>
         <ul class="select clear">
             <li><img src="weixin.jpg" style="width:150px;height:150px"></li>
         </ul>
         <div class="price">商品价：<strong>￥<?php /*echo $total_fee/100; */?>元</strong></div>-->
		 <form action="h5pay.php" method="post">
			 <div class="operation">
				 <a id="one" class="btn-green"  name="pay" style="margin-bottom: 20px" value="1"  onmouseover="get(100)">1元</a>
			 </div> 
			 <div class="operation">
				 <a id="two" class="btn-green"  name="pay" style="margin-bottom: 20px" value="2" onmouseover="get(200)">2元</a>				
			 </div>   
			 <div class="operation">
				 <a id="three" class="btn-green"  name="pay" value="3" onclick="getValue(3)">立即支付</a>
			 <input type="hidden" id="dev_id"   name="test" value="<?php echo $equipment_id ?>"/>  	 
             <input type="hidden" id="money_pay"   name="test" value="<?php echo $money ?>"/>  	 
		 </form>
	 </section>
 </article>
 </body>
            
 <script>
 
 var dev_id = document.getElementById("dev_id").value;
  var money_pay = document.getElementById("money_pay").value;

function getValue( money)
{ 

       
    //var dev=dev_id.split("=")[1];
    //alert("dev_id="+dev_id+"   money="+money);
    
	 //url="http://www.cunzaitech.com/public/WxpayAPI_php_v3/WxpayAPI_php_v3.0.1/example/h5pay_modified.php?equipment_id="+dev+"&money="+money;
	//window.open(url);
     callpay();
}

window.onload=function(){
    if(money_pay==100) document.getElementById("one").className="on";
    if(money_pay==200) document.getElementById("two").className="on";
}
function get(money){
    
    var dev=dev_id.split("=")[1];
    //alert("dev_id="+dev_id+"   money="+money);
    
	 url="http://www.cunzaitech.com/public/WxpayAPI_php_v3/WxpayAPI_php_v3.0.1_another/example/h5pay_modified.php?equipment_id="+dev+"&money="+money;
	window.open(url);
    ElementsByClassName('btn-green');
     for(var i=0;i<arr.length;i++){
         arr[i].onclick=function(){
             if(this.className=='btn-green'){
                 this.className='on';     
             }
         }
     }
    
}


 </script>
 
 <script type="text/javascript">
	//调用微信JS api 支付
	function jsApiCall()
	{
		WeixinJSBridge.invoke(
			'getBrandWCPayRequest',
			<?php echo $jsApiParameters; ?>,
			function(res){
				WeixinJSBridge.log(res.err_msg);
				//alert(res.err_code+res.err_desc+res.err_msg);
                if(res.err_msg=="get_brand_wcpay_request:ok"){
                    alert("支付成功！");
                    //window.location.href='http://www.baidu.com/';
                    window.location.href='./check_order.php';
                }else{
                    
                }
			}
		);
	}

	function callpay()
	{
		if (typeof WeixinJSBridge == "undefined"){
		    if( document.addEventListener ){
		        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
		    }else if (document.attachEvent){
		        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
		        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
		    }
		}else{
		    jsApiCall();
		}
	}
	</script>
	<script type="text/javascript">
	//获取共享地址
	function editAddress()
	{
		WeixinJSBridge.invoke(
			'editAddress',
			<?php echo $editAddress; ?>,
			function(res){
				var value1 = res.proviceFirstStageName;
				var value2 = res.addressCitySecondStageName;
				var value3 = res.addressCountiesThirdStageName;
				var value4 = res.addressDetailInfo;
				var tel = res.telNumber;
				
				alert(value1 + value2 + value3 + value4 + ":" + tel);
			}
		);
	}
	
    
	window.onload = function(){
		if (typeof WeixinJSBridge == "undefined"){
		    if( document.addEventListener ){
		        document.addEventListener('WeixinJSBridgeReady', editAddress, false);
		    }else if (document.attachEvent){
		        document.attachEvent('WeixinJSBridgeReady', editAddress); 
		        document.attachEvent('onWeixinJSBridgeReady', editAddress);
		    }
		}else{
			editAddress();
		}
	};
    
     
	
	</script>
 </html>


