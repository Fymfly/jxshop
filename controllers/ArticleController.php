<?php
namespace controllers;

use models\Article;
use models\Article_category;


class ArticleController extends BaseController{
    // 列表页
    public function index()
    {
        $model = new Article;
        $data = $model->findAll([
            'fields' => 'a.id,title,content,created_at,link,cat_name',
            'join' => 'a left join article_category b on a.article_category_id = b.id',
        ]);
        
        // var_dump('<pre>');
        // var_dump($data);
        // die;
        view('article/index', $data);
    }

    // 显示添加的表单
    public function create()
    {   

        // 取出分类的数据
        $model = new Article_category;
        $data = $model->findAll();

        // 显示表单
        view('article/create', $data);
    }

    // 处理添加表单
    public function insert()
    {
        $model = new Article;
        $model->fill($_POST);
        $model->insert();
        redirect('/article/index');
    }

    // 显示修改的表单
    public function edit()
    {
        $model = new Article;
        $data = $model->findOne($_GET['id']);
        // var_dump('<pre>');
        // var_dump($data);

        // 取出分类
        $model = new \models\Article_category;
        $topCat = $model->findAll();
        // var_dump('<pre>');
        // var_dump($topCat);


        view('article/edit', [
            'data' => $data,    
            'topCat' => $topCat['data'],
        ]);
    }

    // 修改表单的方法
    public function update()
    {
        $model = new Article;
        $model->fill($_POST);
        $model->update($_GET['id']);
        redirect('/article/index');
    }

    // 删除
    public function delete()
    {
        $model = new Article;
        $model->delete($_GET['id']);
        redirect('/article/index');
    }
}