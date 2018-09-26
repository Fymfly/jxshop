<?php
namespace libs;

class DB {

    private static $_obj = null;
    private function __construct(){}

    
    private $_pdo;
    private function __clone(){
        // 连接数据库
        $this->_pdo = new \PDO('mysql:host=172.0.0.1;dbname=jxshop', 'root', '');
        // 设置编码
        $this->_pdo->exec('SET NAMES utf8');
    }

    
    // 返回唯一的对象
    public static function make() {

        if(self::$_obj === null) {

            self::$_obj = new self;
        }

        return self::$_obj;
    }
}