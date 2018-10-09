<?php
namespace models;

class Role extends Model
{
    // 设置这个模型对应的表
    protected $table = 'role';
    // 设置允许接收的字段
    protected $fillable = ['role_name'];


    // 添加、修改角色之后自动加载被执行
    // 获取添加完之后的ID : $this->data['id']
    protected function _after_write() {

        $stmt = $this->_db->prepare("INSERT INTO role_privlege(pri_id,role_id) VALUES(?,?)");
        // 循环所有勾选的权限ID插入到中间表
        foreach($_POST['pri_id'] as $v)
        {
            $stmt->execute([
                $v,
                $this->data['id'],
            ]);
        }
    }


    // 删除角色及权限（在删除之前执行）
    protected function _before_delete() {

        $stmt = $this->_db->prepare("delete from role_privlege where role_id=?");
        $stmt->execute([
            $_GET['id']
        ]);
    }
}