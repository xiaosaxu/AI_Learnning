<?php
session_start();
/* 
 * 黎明互联
 * https://www.liminghulian.com/
 */

class  Base
{
    const KEY = 'kPbYKYCr82B0uKRWBHDnRRZj2Q8qjMQP'; //请修改为自己的
    const MCHID = '1499996382'; //请修改为自己的
    const RPURL = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
    const APPID = 'wx5fe0340586a0470c';//请修改为自己的
    const CODEURL = 'https://open.weixin.qq.com/connect/oauth2/authorize?';
    const OPENIDURL = 'https://api.weixin.qq.com/sns/oauth2/access_token?';
    const SECRET = '5d499733e54aef7ce212b43146c9e56e';//请修改为自己的
    //获取用户openid 为避免重复请求接口获取后应做存储
   
	/**  
	* 获取签名 
	* @param array $arr
	* @return string
	*/  
    public function getSign($arr){
        //去除空值
        $arr = array_filter($arr);
        if(isset($arr['sign'])){
            unset($arr['sign']);
        }
        //按照键名字典排序
        ksort($arr);
        //生成url格式的字符串
       $str = $this->arrToUrl($arr) . '&key=' . self::KEY;
       //var_dump(strtoupper(md5($str))."<br /><br /><br /><br />****");
       return strtoupper(md5($str));
    }
    /**  
	* 获取带签名的数组 
	* @param array $arr
	* @return array
	*/  
    public function setSign($arr){
        $arr['sign'] = $this->getSign($arr);;
        //var_dump($arr['sign']."<br /><br /><br /><br /><br /><br />++");
        return $arr;
    }
	/**  
	* 数组转URL格式的字符串
	* @param array $arr
	* @return string
	*/
    public function arrToUrl($arr){
        return urldecode(http_build_query($arr));
    }
    
    
   
    
    //数组转xml
    function ArrToXml($arr)
    {
            if(!is_array($arr) || count($arr) == 0) return '';

            //var_dump("------".$arr."-------");
            //echo "<br /><br /><br /><br />";
            $xml = "<xml>";
                                                                                        /*
                                                                                            [
                                                                                             "mch_appid"=>'wx5fe0340586a0470c',
                                                                                             "mchid"=>'1500772721',
                                                                                             "nonce_str"=>'e9193816a6611e7489c1376cd7055718',
                                                                                             "partner_trade_no"=>'20180422112319',
                                                                                             "openid"=>'oxzKS1JN1r9vipiKcdFo5Nl3tNWs',
                                                                                             "check_name"=>'NO_CHECK',
                                                                                             "amount"=>'1',
                                                                                             "desc"=>'test',
                                                                                             "spbill_create_ip"=>'114.215.84.155',
                                                                                             "sign"=>'7E2F8DA19B534F5E84426E0B3F5C55B6'
                                                                                            ]
                                                                                        */
            
            foreach ($arr as $key=>$val)
            {
                    if (is_numeric($val)){
                            $xml.="<".$key.">".$val."</".$key.">";
                    }else{
                            $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
                    }
                    
            }
            /*$xml.= "<amount>".$arr['amount']."</amount>
                    <check_name>".$arr['check_name']."</check_name>
                    <desc>"."test</desc>
                    <mch_appid>".$arr['mch_appid']."</mch_appid>
                    <mchid>".$arr['mchid']."</mchid>
                    <nonce_str>".$arr['nonce_str']."</nonce_str>
                    <openid>".$arr['openid']."</openid>
                    <partner_trade_no>".$arr['partner_trade_no']."</partner_trade_no>
                    <sign>".$arr['sign']."</sign>
                    <spbill_create_ip>".$arr['spbill_create_ip']."</spbill_create_ip>
                    <sign>".$arr['sign']."</sign>";
            
            /*$xml .= "<mch_appid>".$arr['mch_appid']."</mch_appid>
                    <mchid>".$arr['mchid']."</mchid>
                    <nonce_str>".$arr['nonce_str']."</nonce_str>
                    <partner_trade_no>".$arr['partner_trade_no']."</partner_trade_no>
                    <openid>".$arr['openid']."</openid>
                    <check_name>".$arr['check_name']."</check_name>
                    <amount>".$arr['amount']."</amount>
                    <desc>".$arr['desc']."</desc>
                    <spbill_create_ip>".$arr['spbill_create_ip']."</spbill_create_ip>
                    <sign>".$arr['sign']."</sign>";*/
            $xml.="</xml>";
            //echo "------".$xml."-------";
            
            /*$xml="<xml>";            
            
            $xml.="<mch_appid>".$dataArr['mch_appid']."</mch_appid>
            <mchid>".$dataArr['mchid']."</mchid>
            <nonce_str>".$dataArr['nonce_str']."</nonce_str>
            <partner_trade_no>".$dataArr['partner_trade_no']."</partner_trade_no>
            <openid>".$dataArr['openid']."</openid>
            <check_name>".$dataArr['check_name']."</check_name>
            <re_user_name>".$dataArr['re_user_name']."</re_user_name>
            <amount>".$dataArr['amount']."</amount>
            <desc>".$dataArr['desc']."</desc>
            <spbill_create_ip>".$dataArr['spbill_create_ip']."</spbill_create_ip>
            <sign>".$sign."</sign>";
            
            $xml.="</xml>"*/
            return $xml; 
    }
	
    //Xml转数组
    function XmlToArr($xml)
    {	
            if($xml == '') return '';
            libxml_disable_entity_loader(true);
            $arr = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);		
            return $arr;
    }
    function postData($url,$postfields){
       
        $ch = curl_init();
        $params[CURLOPT_URL] = $url;    //请求url地址
        $params[CURLOPT_HEADER] = false; //是否返回响应头信息
        $params[CURLOPT_RETURNTRANSFER] = true; //是否将结果返回
        $params[CURLOPT_FOLLOWLOCATION] = true; //是否重定向
        $params[CURLOPT_POST] = true;
        $params[CURLOPT_POSTFIELDS] = $postfields;
        $params[CURLOPT_SSL_VERIFYPEER] = false;
        $params[CURLOPT_SSL_VERIFYHOST] = false;
        //以下是证书相关代码
             $params[CURLOPT_SSLCERTTYPE] = 'PEM';
             $params[CURLOPT_SSLCERT] = './cert/apiclient_cert.pem';
             $params[CURLOPT_SSLKEYTYPE] = 'PEM';
             $params[CURLOPT_SSLKEY] = './cert/apiclient_key.pem';

          curl_setopt_array($ch, $params); //传入curl参数
          $content = curl_exec($ch); //执行
          curl_close($ch); //关闭连接
          return $content;
    }
}