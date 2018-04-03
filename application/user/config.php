<?php
//配置文件
return [
    'session' => [
        'prefix' => 'yunprint',
        'type' => '',
        'auto_start' => true,
        // redis主机
        'host' => '127.0.0.1',
        // redis端口
        'port' => 6379,
        // 密码
        'password' => '',
    ],
    //模板参数替换
    'view_replace_str' => array(
        '__USER__' => '/YunPrint/public/user',
        '__USERCSS__' => '/YunPrint/public/static/user/css',
        '__USERJS__' => '/YunPrint/public/static/user/js',
        '__USERIMG__'  => '/YunPrint/public/static/user/img'
    )
];