<?php
    $appid='wxe5809c42e6c00d2d';
    $redirect_uri = urlencode ( 'http://dingcanphp.applinzi.com/getUserInfo.php' );//将字符串以 URL 编码。
    $url ="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
    header("Location:".$url);//header() 函数向客户端发送原始的 HTTP 报头。






?>