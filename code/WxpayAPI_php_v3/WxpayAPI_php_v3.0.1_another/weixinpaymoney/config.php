<?php
/*
    * 配置内容可以修改   XXXXXXXXXXXXXXXXXX替换成自己信息
    * 微信公众号配置
    * 用于微信企业付款
 */
$cpy_appid = 'wx5fe0340586a0470c';                  //公众号appid
$cpy_mchid = '1500772721';                          //商户id
$cpy_key = '2F5xWVowCZknlScUJhZNp7gYB3wu9ydd';      //商户key
$cpy_secret = '5d499733e54aef7ce212b43146c9e56e';   //公众号secret
$cpy_nonce_str = time().rand(100000, 999999);   //随机字符串
$cpy_order_str = time().rand(100000, 999999); // 唯一订单号
$cpy_openid = 'oxzKS1JN1r9vipiKcdFo5Nl3tNWs';       //公众号appid 所获取的用户openid


/*
    * curl请求数据
 */
function curl_get($url){  
    $ch = curl_init();    
    curl_setopt($ch, CURLOPT_URL, $url);    
     //参数为1表示传输数据，为0表示直接输出显示。  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
     //参数为0表示不带头文件，为1表示带头文件  
    curl_setopt($ch, CURLOPT_HEADER,0);  
    $output = curl_exec($ch);   
    curl_close($ch);   
    return $output;  
}  

/**
    * 以post方式提交xml到对应的接口url
    * 
    * @param string $xml  需要post的xml数据
    * @param string $url  url
    * @param bool $useCert 是否需要证书，默认不需要
    * @param int $second   url执行超时时间，默认30s
    * @throws WxPayException
*/

function postXmlCurl($xml, $url, $useCert = false, $second = 30){		

    $ch = curl_init();
    //设置超时
    curl_setopt($ch, CURLOPT_TIMEOUT, $second);

    curl_setopt($ch,CURLOPT_URL, $url);

    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,true);
    curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,true);//严格校验
    //
    //设置header
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    //要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
    
    $cpy_sslcert_path =  getcwd().'/cert/apiclient_cert.pem';
    $cpy_sslkey_path =  getcwd().'/cert/apiclient_key.pem';
    
    if($useCert == true){
        //设置证书
        //使用证书：cert 与 key 分别属于两个.pem文件
        curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLCERT, $cpy_sslcert_path);
        curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLKEY, $cpy_sslkey_path);
    }
    //post提交方式
    $aa=curl_setopt($ch, CURLOPT_POST, TRUE);
    $ab=curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
    //$aa=curl_setopt($ch, CURLOPT_POST, TRUE);
    //$ab=curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
    //var_dump($aa);                                        //bool(true)
    //var_dump($ab);                                        //bool(true)
    //运行curl
    $data = curl_exec($ch);
    //var_dump($data);                                      //bool(true)
    //返回结果
    if($data){
        curl_close($ch);
        return $data;
    } else { 
        $error = curl_errno($ch);
        curl_close($ch);
        return $error."err";
    }
}

/**
 * 	作用：格式化参数，签名过程需要使用
 */
function formatBizQueryParaMap($paraMap, $urlencode)
{
    $buff = "";
    ksort($paraMap);
    foreach ($paraMap as $k => $v)
    {
        if($urlencode)
        {
            $v = urlencode($v);
        }
        $buff .= $k . "=" . $v . "&";
    }
    $reqPar;
    if (strlen($buff) > 0)
    {
        $reqPar = substr($buff, 0, strlen($buff)-1);
    }
    return $reqPar;
}

/**
 * 	作用：生成签名
 *      $obj 数组
 *      $key 商户key
 */
function getSign($Obj,$key)
{
    foreach ($Obj as $k => $v)
    {
        $Parameters[$k] = $v;
    }
    //签名步骤一：按字典序排序参数
    ksort($Parameters);
    $String = formatBizQueryParaMap($Parameters, false);
    //签名步骤二：在string后加入KEY
    $String = $String."&key=".$key;
    //签名步骤三：MD5加密
    $String = md5($String);
    //签名步骤四：所有字符转为大写
    $result = strtoupper($String);
    return $result;
}

?>