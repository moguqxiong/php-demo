<?php


namespace app\admins\controller;

use think\Controller;
use Util\data\Sysdb;


class Account extends Controller
{
    public function login()
    {
        return $this->fetch();
    }

    public function doLogin()
    {
        $username = trim(input('post.username'));
        $pwd = trim(input('post.password'));
        $verifyCode = trim(input('post.verifyCode'));
        if ($username === '') {
            exit(json_encode(array('code' => 1, 'msg' => '用户名不能为空')));
        }
        if ($pwd === '') {
            exit(json_encode(array('code' => 1, 'msg' => '密码不能为空')));
        }
        if ($verifyCode === '') {
            exit(json_encode(array('code' => 1, 'msg' => '验证码不能为空')));
        }
        if (!captcha_check($verifyCode)) {
            exit(json_encode(array('code' => 1, 'msg' => '验证码不正确')));
        }
        $db = new Sysdb();
        $res = $db->table('admins')->where(array('username' => $username))->item();
        if (!$res) {
            exit(json_encode(array('code' => 1, 'msg' => '该用户不存在')));
        }
        if (md5($pwd) != $res['password']) {
            exit(json_encode(array('code' => 1, 'msg' => '密码不正确')));
        }
        if ($res['status'] == 1) {
            exit(json_encode(array('code' => 1, 'msg' => '账号已经被禁用')));
        }
        session('admin', $res);
        exit(json_encode(array('code' => 0, 'msg' => '登录成功')));


    }
}