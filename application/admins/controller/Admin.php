<?php


namespace app\admins\controller;

use \think\controller;
use think\Db;
use \Util\data\Sysdb;

class Admin extends BaseAdmin
{
    public function index()
    {
        $data['lists'] = $this->db->table('admins')->lists();
        $data['groups'] = $this->db->table('admin_groups')->cates('gid');
        $this->assign('data', $data);
        return $this->fetch();
    }


    public function add()
    {
        $id = input('get.id');
        $data['item'] = $this->db->table('admins')->where(array('id' => $id))->item();
        $data['groups'] = $this->db->table('admin_groups')->cates('gid');
        $this->assign('data', $data);
        return $this->fetch();
    }

    //插入管理员
    public function insert()
    {
        $data['id'] = trim(input('post.id'));
        $data['username'] = trim(input('post.username'));
        $data['truename'] = trim(input('post.realname'));
        $password = trim(input('post.password'));
        $data['status'] = (boolean)(input('post.status'));
        $data['gid'] = (int)(input('post.gid'));
        $data['addtime'] = time();
        //dump($data['id']) ;
        if ($data['username'] == '') {
            exit(json_encode(array('code' => 1, 'msg' => '请填写用户名')));
        }
        if ($data['truename'] == '') {
            exit(json_encode(array('code' => 1, 'msg' => '请输入真实姓名')));
        }
        if ($data['id'] == '' && $password == '') {
            exit(json_encode(array('code' => 1, 'msg' => '请输入密码')));
        } else {
            $data['password'] = md5($password);
        }
        if ($data['gid'] == 0) {
            exit(json_encode(array('code' => 1, 'msg' => '请选择角色')));
        }
        $f = true;
        if ($data['id'] != '') {
            $this->db->table('admins')->where(array('id' => $data['id']))->update($data);
        } else {
            $res = $this->db->table('admins')->where(array('username' => $data['username']))->item();
            if ($res) {
                exit(json_encode(array('code' => 1, 'msg' => '用户已存在')));
            }
            $f = $this->db->table('admins')->insert($data);
        }
        if ($f) {
            exit(json_encode(array('code' => 0, 'msg' => '保存成功')));
        } else {
            exit(json_encode(array('code' => 1, 'msg' => '保存失败')));
        }
    }

    //删除管理员
    public function delete()
    {
        $id = (int)input('post.id');
        $res = $this->db->table('admins')->where(array('id' => $id))->delete();
        if ($res) {
            exit(json_encode(array('code' => 0, 'msg' => '删除成功')));
        } else {
            exit(json_encode(array('code' => 1, 'msg' => '删除失败')));
        }

    }
}