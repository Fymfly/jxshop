<?php
namespace controllers;

use models\Member;

class MemberController extends BaseController{
    // 列表页
    public function index()
    {
        $model = new Member;
        $data = $model->findAll();
        view('member/index', $data);
    }

    // 显示添加的表单
    public function create()
    {
        view('member/create');
    }

    // 处理添加表单
    public function insert()
    {
        $model = new Member;
        $model->fill($_POST);
        $model->insert();
        redirect('/member/index');
    }

    // 显示修改的表单
    public function edit()
    {
        $model = new Member;
        $data=$model->findOne($_GET['id']);
        view('member/edit', [
            'data' => $data,    
        ]);
    }

    // 修改表单的方法
    public function update()
    {
        $model = new Member;
        $model->fill($_POST);
        $model->update($_GET['id']);
        redirect('/member/index');
    }

    // 删除
    public function delete()
    {
        $model = new Member;
        $model->delete($_GET['id']);
        redirect('/member/index');
    }
}