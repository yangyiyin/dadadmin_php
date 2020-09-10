<?php
/**
 * User: yyy
 * Date: 2020/9/10
 * Time: 10:59
 */

//设置跨域
$allow_origin = array(
    'http://localhost:8080',
);

//跨域访问的时候才会存在此字段
$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
if (in_array($origin, $allow_origin)) {
    header('Access-Control-Allow-Origin:' . $origin);
//允许访问的方式
    header('Access-Control-Allow-Method:GET,POST,PUT,PATCH,DELETE,HEAD,OPTIONS,HEAD');
//允许自定义的头部参数
    header("Access-Control-Allow-Headers:Origin,X-Requested-With,Content-Type,Accept,Authorization");
}

$method = $_SERVER['REQUEST_METHOD'] ? $_SERVER['REQUEST_METHOD'] : '';
if (strtoupper($method) == 'OPTIONS') {
    //浏览器端自定义head参数后的预请求，直接返回，不需进入具体业务
    exit('');
}