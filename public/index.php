<?php
define('ROOT', __DIR__ . '/../');

// 设置时区
date_default_timezone_set('PRC');


session_start();

// 引入函数文件
require(ROOT.'libs/functions.php');


// 引入 composer 安装的包
require(ROOT.'vendor/autoload.php');

// 自动加载
function autoload($class) {

    $path = str_replace('\\','/', $class);
    require ROOT.$path.'.php';
}
spl_autoload_register('autoload');


// 解析路由
$controller = '\controllers\IndexController';
$action = 'index';
if(isset($_SERVER['PATH_INFO'])) {
    
    $router = explode('/', $_SERVER['PATH_INFO']);
    $controller =   '\controllers\\'.ucfirst($router[1]) . 'Controller';
    $action = $router[2];
}
// echo '<pre>';
// var_dump($_SERVER);
// die;

$C = new $controller;
$C->$action();