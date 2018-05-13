<?php
require_once('config.php');

//封装数据
$ip = $_SERVER['REMOTE_ADDR'];
$dataArr = array(
    'mch_appid' => $cpy_appid,
    'mchid' => $cpy_mchid,
    'nonce_str' => $cpy_nonce_str,
    'partner_trade_no' => $cpy_order_str,
    'openid' => $cpy_openid,
    'check_name' => 'OPTION_CHECK',
    're_user_name' => 'test', //填写对应openid真实姓名
    'amount' => 100, //以分为单位,必须大于100
    'desc' => 'happynewyear',
    'spbill_create_ip' => $ip,
);

//生成签名
$sign=getSign($dataArr,$cpy_key);

$xml = '<xml>
            <mch_appid>'.$dataArr['mch_appid'].'</mch_appid>
            <mchid>'.$dataArr['mchid'].'</mchid>
            <nonce_str>'.$dataArr['nonce_str'].'</nonce_str>
            <partner_trade_no>'.$dataArr['partner_trade_no'].'</partner_trade_no>
            <openid>'.$dataArr['openid'].'</openid>
            <check_name>'.$dataArr['check_name'].'</check_name>
            <re_user_name>'.$dataArr['re_user_name'].'</re_user_name>
            <amount>'.$dataArr['amount'].'</amount>
            <desc>'.$dataArr['desc'].'</desc>
            <spbill_create_ip>'.$dataArr['spbill_create_ip'].'</spbill_create_ip>
            <sign>'.$sign.'</sign>
        </xml>';
        //echo $xml;
$url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';

echo postXmlCurl($xml, $url, true);


//成功
?>