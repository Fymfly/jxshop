<?php
// 为了使用 composer 下载的包
require('./vendor/autoload.php');

use Qiniu\Storage\UploadManager;        // 向七牛云上传、下载等功能
use Qiniu\Auth;                         // 登录、验证（七牛云）

// 连接数据库
$pdo = new \PDO('mysql:host=127.0.0.1;dbname=jxshop', 'root','');

// 连接 Redis
$client = new \Predis\Client([
    'scheme' => 'tcp',
    'host'   => 'localhost',
    'port'   => 6379,
]);

// 设置 socket 永不超时（PHP脚本默认只能执行60秒）-> 这个可以一直在后台执行
ini_set('default_socket_timeout', -1); 

// 上传七牛云
$accessKey = 'A3r8ukhH7VSbRvt1EXzXS8hQQSTWWtWWO_lbjFRB';
$secretKey = '6RdEauNpfK20Qrs0PE7vnat6wwYIuV5SJ9dGRlbh';
$domain = 'http://pixne1z0f.bkt.clouddn.com';
// 配置参数
$bucketName = 'vue-shop';   // 创建的 bucket(新建的存储空间的名字)

// 登录获取令牌
$auth = new Auth($accessKey, $secretKey);

// 登录到七牛云（登录获取令牌）
// 第一参数：存储空间的名称
// 第二参数：策略
// 第三参数：令牌的有效期
$expire = 86400 * 365 * 10; // 令牌过期时间10年
$token = $auth->uploadToken($bucketName, null, $expire);

$upManager = new UploadManager();


// 循环监听一个列表
while(true)
{
    // 从队列中取数据，设置为永久不超时（如果队列里面是空的，就一直阻塞在这）
    $rawdata = $client->brpop('jxshop:qiniu', 0);
    // 处理数据
    $data = unserialize($rawdata[1]); // 转成数组
    // 获取文件名
    $name = ltrim(strrchr($data['logo'], '/'), '/');
    // 上传的文件
    $file = './public'.$data['logo'];
    list($ret, $error) = $upManager->putFile($token, $name, $file);
    // 判断是否成功
    if ($error !== null) {
        // 如果失败，重新将数据放回队列
        $client->lpush('jxshop:qiniu', $rawdata[1]);        
    } else {
        // 更新数据库
        $new = $domain.'/'.$ret['key'];
        $sql = "UPDATE ".$data['table']." SET ".$data['column']."='$new' WHERE id=".$data['id'];
        $pdo->exec($sql);
        // 删除本地文件
        @unlink($file);
        echo 'ok';
    }
}