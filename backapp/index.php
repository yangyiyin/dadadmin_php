<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// 应用入口文件
// 检测PHP环境
if(version_compare(PHP_VERSION,'7.1.0','<'))  die('require PHP > 7.1.0 !');
//test
// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',True);
if (APP_DEBUG) {
    error_reporting(E_ALL);
}

// 定义应用目录
define('APP_PATH','.'.DIRECTORY_SEPARATOR);
define('APP_PATH_ABS',dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('TIMESTAMP',time());

include_once APP_PATH . 'cors.php';

// 引入ThinkPHP入口文件
require  '../ThinkPHP/ThinkPHP.php';
//
//// 亲^_^ 后面不需要任何代码了 就是如此简单