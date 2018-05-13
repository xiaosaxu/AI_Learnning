<?php
header("Content-type: text/html; charset=utf-8");
session_start();
include './Base.php';
include './Rsa.php';
/* 
 * 黎明互联
 * https://www.liminghulian.com/
 */

 
 
 
 
 
 
/*-------------------------------------------------------------------------------------------------- 
echo "系统当前时间戳为：";
echo "";
echo time();
//<!--JS 页面自动刷新 -->
echo ("<script type=\"text/javascript\">");
echo ("function fresh_page()");    
echo ("{");
echo ("window.location.reload();");
echo ("}"); 
echo ("setTimeout('fresh_page()',1000);");      
echo ("</script>");

/*--------------------------------------------------------------------------------------------------*/

 
 


$money_pay=$_GET['moneyPay'];
$deviceId=$_GET['deviceId'];
$moneyPay=0.9*$money_pay;
//var_dump($moneyPay);




if(!$_SESSION['randomNum']){


$randomNum=rand(100,10000);                          /*为阻止程序重复连续执行，给session写入随机数进行验证或者插入数据库数据*/
echo $randomNum;
$_SESSION['randomNum']=$randomNum;






class WxComPay extends Base
{
    private $params;
    const PAYURL = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
    const SEPAYURL = "https://api.mch.weixin.qq.com/mmpaymkttransfers/gettransferinfo";
    const PKURL = "https://fraud.mch.weixin.qq.com/risk/getpublickey";
    const BANKPAY = "https://api.mch.weixin.qq.com/mmpaysptrans/pay_bank";
    public function getPuyKey(){
        $this->params = [
            'mch_id'    => self::MCHID,//商户ID
            'nonce_str' => md5(time()),
            'sign_type' => 'MD5'
        ];
         //将数据发送到接口地址
        return $this->send(self::PKURL);
    }
    public function comPay($data){
        //构建原始数据
        $this->params = [
            'mch_appid'         => self::APPID,//APPid,
            'mchid'             => self::MCHID,//商户号,
            'nonce_str'         => md5(time()), //随机字符串
            'partner_trade_no'  => date('YmdHis'), //商户订单号
            'openid'            => $data['openid'], //用户openid
            'check_name'        => 'NO_CHECK',//校验用户姓名选项 NO_CHECK：不校验真实姓名 FORCE_CHECK：强校验真实姓名
            //'re_user_name'    => '',//收款用户姓名  如果check_name设置为FORCE_CHECK，则必填用户真实姓名
            'amount'            => $data['price'],//金额 单位分
            'desc'              => '测试付款',//付款描述
            'spbill_create_ip'  => "114.215.84.155",//调用接口机器的ip地址
        ];
        //将数据发送到接口地址
        return $this->send(self::PAYURL);
    }
    public function bankPay($data){
        $this->params = [
            'mch_id'    => self::MCHID,//商户号
            'partner_trade_no'   => date('YmdHis'),//商户付款单号
            'nonce_str'           => md5(time()), //随机串
            'enc_bank_no'         => $data['enc_bank_no'],//收款方银行卡号RSA加密
            'enc_true_name'       => $data['enc_true_name'],//收款方姓名RSA加密
            'bank_code'           => $data['bank_code'],//收款方开户行
            'amount'              => $data['amount'],//付款金额
        ];
         //将数据发送到接口地址
        return $this->send(self::BANKPAY);
    }
    public function searchPay($oid){
        $this->params = [
            'nonce_str'  => md5(time()),//随机串
            'partner_trade_no'  => $oid, //商户订单号
            'mch_id'  => self::MCHID,//商户号
            'appid'  => self::APPID //APPID
        ];
         //将数据发送到接口地址
        return $this->send(self::SEPAYURL);
    }
    public function sign(){
        return $this->setSign($this->params);
    }
    public function send($url){
        $res = $this->sign();
        $xml = $this->ArrToXml($res);
        //var_dump("**<br /><br /><br /><br />**".$res);
        //var_dump($xml);
        //var_dump("$$<br /><br /><br />".$url."$$<br />");
       $returnData = $this->postData($url, $xml);
       return $this->XmlToArr($returnData);
    }
}

$obj = new WxComPay();
/* 
 * 付款到零钱
 */
 
/* 
$data = [
  'openid'  => 'oFjt8t1WUNtxNeD7-SvOgFHt4CJQ',
  'openid'  => 'oxzKS1CgI7U-WPwbxuwTQJ9ouwzk',
  'price'   => '1'
];
*/
$data = [
  'openid'  => 'oxzKS1JN1r9vipiKcdFo5Nl3tNWs',
  'price'   => $moneyPay
];
$res = $obj->comPay($data);
//var_dump("###<br /><br /><br /><br /><br />#".$data);

//查询
/*
$oid = "20180129201209";
$res = $obj->searchPay($oid);
 * 
 */

//获取公钥
/*
$res = $obj->getPuyKey();
file_put_contents('./cert/pubkey.pem', $res['pub_key']);
 * 
 */
//企业付款到银行卡
/*
$rsa = new RSA(file_get_contents('./cert/newpubkey.pem'), '');
$data = [
     'enc_bank_no'         => $rsa->public_encrypt('1234342343234234'),//收款方银行卡号RSA加密
     'enc_true_name'       => $rsa->public_encrypt('李明'),//收款方姓名RSA加密
     'bank_code'           => '1002',//收款方开户行
     'amount'              => '100',//付款金额
];

$res = $obj->bankPay($data);
 * 
 
$res = $obj->getPuyKey();
file_put_contents('./cert/pubkey.pem', $res['pub_key']);
 
*/
 
 
#echo '<pre>';
#print_r($res);


//if($res["result_code"]=="SUCCESS"){                                  /******************进行判断，如果付款成功，执行获取设备信息&&发送指令*****************************/




                                                                       /******************请求onenet设备信息，展示current_value值****************************************/

                                                                       
                                                                       
                                                                       
require '../../onenet/restful_api/PHPSDK/OneNetApi.php';       

//$apikey = 'QfI9JKZy9=nfq=ulp0KpAgBxPL8=';
$apikey = 'PShAZRndcHpzg4AOqcofC6lUffY=';
$apiurl = 'http://api.heclouds.com';

//创建api对象
$sm = new OneNetApi($apikey, $apiurl);

//----------------获取设备信息-------------
$device_id = '26313403';
$device = $sm->device($device_id);
$error_code = 0;  
$error = '';  
if (empty($device)) {
    //处理错误信息
    $error_code = $sm->error_no();
    $error = $sm->error();
}
//var_dump("<br /><br /><br /><br />###".$device);
var_dump("<br /><br /><br /><br />###".$device[id]);
echo "----------------------------------------------------";
                                                                       
                                                                       
                                                                       
                                                                       
                                                                       
$header[]="api-key:PShAZRndcHpzg4AOqcofC6lUffY=";//此处写成自己的API-KEY值  
$url="http://api.heclouds.com/devices/".$deviceId."/datastreams/";/*获取数据流为**的数据值，注意此处设备ID号以及申请获取的数据流ID号都应根据自己的OneNet平台设备ID号，希望获取的数据流ID号进行更改*/   
//用于获取从OneNet平台返还的数据  
function get($url, $header)  
{  
       //1.初始化，创建一个新cURL资源  
       $ch=curl_init();  
       //2.设置URL和相应的选项  
       curl_setopt($ch,CURLOPT_URL,$url);//需要获取的URL地址，也可以在curl_init()函数中设置  
       curl_setopt($ch,CURLOPT_HTTPHEADER,$header);//启用时会将头文件的信息作为数据流输出  
       curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);//将curl_exec()获取的信息以字符串返回，而不是直接输出。  
       curl_setopt($ch,CURLOPT_HEADER,0);//启用时会将头文件的信息作为数据流输出。  
       if(curl_exec($ch)=== false) //curl_error()返回当前会话最后一次错误的字符串  
       {  
             die("Curlerror: ".curl_error($ch));  
       }  
       $response =curl_exec($ch);//获取返回的文件流  
    curl_close($ch);  
    return $response;  
}  
  
$output=get($url,$header);  
$output_array=json_decode($output,true);  
print_r($output_array);
var_dump("<br /><br />"."设备号为".$device[id]."的current_value值是:".$output_array[data][0][current_value]);
$currentValue = $output_array[data][0][current_value];
$currentValue_last = substr($currentValue,16);                         /***************截取current_value#后面的值***************/
echo $currentValue_last;
/* 
print_r($output_array["data"]["update_at"]); 
print_r($output_array["data"]["current_value"]); 

*/ 





                                                                       /******************向onenet设备发送指令，这里是string字符串格式***********************************/


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
$uuid="api_create_test_onenet";
        
for($xunhuan=1,$nun_insert=0;$xunhuan<9;$xunhuan++)
{
    $nun_insert +=0.1;
    $datas_add =array(time()=>$nun_insert,time()+1=>$nun_insert,time()+2=>$nun_insert,time()+3=>$nun_insert);
    $device = $sm->datapoint_add($device_id,$uuid,$datas_add);
}
    //$json = '{"AV":11,"CC":33,"34":"ER","GG":"error","err":"error"}';
    
    $devId=substr($currentValue,0,16);                                 /***************截取current_value#前面的值***************/
    $addTime=5;
    $str = $devId.$addTime;                                           
    echo "|||||".$str."_______________";
    
    $de=$sm->send_data_to_mqtt_use_device_id($device_id,$str,NULL);
    
    //echo 'a';
    //var_dump($de);
    
echo "设备开始运行，运行时间为：";   

unset($_SESSION['randomNum']);

 //}
}else{
    return;
}