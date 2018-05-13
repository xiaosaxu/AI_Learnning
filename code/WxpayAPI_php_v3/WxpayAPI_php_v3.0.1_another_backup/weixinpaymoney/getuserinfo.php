<?php
    header("content-type:text/html;charset=utf-8");
    $code = $_GET["code"];//预定义的 $_GET 变量用于收集来自 method="get" 的表单中的值。
    if (isset($_GET['code'])){//判断code是否存在
        $userinfo = getUserInfo($code);
        $xinxi = $userinfo['nickname'];//获取nickname对应的值,即用户名
        print '<h2 style="text-align:center">用户名：'.$xinxi.'</h2>';//打印输出
    }else{
        echo "NO CODE";
    }
    
    function getUserInfo($code)
    {
        $appid = "wxe5809c42e6c00d2d";
        $appsecret = "506eba1b31666f6052e9c703f906368b";

        //Get access_token
        $access_token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appsecret&code=$code&grant_type=authorization_code";
        $access_token_json = https_request($access_token_url);//自定义函数
        $access_token_array = json_decode($access_token_json,true);//对 JSON 格式的字符串进行解码，转换为 PHP 变量，自带函数
        //获取access_token
        $access_token = $access_token_array['access_token'];//获取access_token对应的值
        //获取openid
        $openid = $access_token_array['openid'];//获取openid对应的值

        //Get user info
        $userinfo_url = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid";
        $userinfo_json = https_request($userinfo_url);
        $userinfo_array = json_decode($userinfo_json,ture);
        return $userinfo_array;
    }

    function https_request($url)//自定义函数,访问url返回结果
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl,  CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        if (curl_errno($curl)){
            return 'ERROR'.curl_error($curl);
        }
        curl_close($curl);
        return $data;
    }




?>