<?php
namespace models;
/*
    所有模型的父模型
    在这里实现所有表的：添加、修改、删除、查询翻页等功能
*/ 

class Model 
{   
    protected $_db;

    // 操作的表名，由子类设置
    protected $table;

    // 表单中的数据，由控制器设置值
    protected $data;

    // 构造函数
    public function __construct() {

        $this->_db = \libs\DB::make();
    }

    public function insert() {

        $keys=[];
        $values=[];
        $token=[];
        
        foreach($this->data as $k => $v) {

            $keys[] = $k;
            $values[] = $v;
            $token[] = '?';
        }
    //    var_dump($this->data);

        $keys = implode(',', $keys);
        $token = implode(',', $token);  // ?,?,?,?

        $sql = "INSERT INTO {$this->table}($keys) VALUES($token)";

        $stmt = $this->_db->prepare($sql);
        return $stmt->execute($values);

    }

    public function update() {

        
    }

    public function delete() {

        
    }

    public function findAll() {

        
    }

    public function findOne() {

        
    }

    // 接收表单中的数据
    public function fill($data) {

        // 判断是否在 白名单 中
        foreach($data as $k => $v) {
            
            if(!in_array($k, $this->fillable)) {

                unset($data[$k]);
            }
        }
        $this->data = $data;
        
    }
}