<?php


namespace app\admins\controller;

use Util\data\Sysdb;


class Menu extends BaseAdmin
{
    public function index()
    {
        $pid = (int)input('get.pid');
        $data['lists'] = $this->db->table('admin_menus')->where(array('pid' => $pid))->lists();
        $backid = 0;
        if ($pid > 0) {
            $parenst = $this->db->table('admin_menus')->where(array('mid' => $pid))->item();
            $backid = $parenst['pid'];
        }
        $this->assign('pid', $pid);
        $this->assign('backid', $backid);
        $this->assign('data', $data);
        return $this->fetch();
    }

    public function save()
    {
        $ords = input('post.ord/a');
        $titles = input('post.title/a');
        $controllers = input('post.controller/a');
        $methods = input('post.method/a');
        $ishiddens = input('post.ishiddens/a');
        $status = input('post.status/a');
        $pid = (int)input('post.pid');
        foreach ($ords as $key => $value) {
            $data['pid'] = $pid;
            //新增
            $data['ord'] = $value;
            $data['title'] = $titles[$key];
            $data['controller'] = $controllers[$key];
            $data['method'] = $methods[$key];
            $data['ishidden'] = isset($ishiddens[$key]) ? 1 : 0;
            $data['status'] = isset($status[$key]) ? 1 : 0;
            if ($key == 0 && $data['title']) {
                $this->db->table('admin_menus')->insert($data);
            }
            if ($key > 0) {
                if ($data['title'] == '' && $data['controller'] == '' && $data['method'] == '') {
                    $this->db->table('admin_menus')->where(array('mid' => $key))->delete();
                    $this->db->table('admin_menus')->where(array('pid' => $key))->delete();
                } else {
                    $this->db->table('admin_menus')->where(array('mid' => $key))->update($data);
                }
            }
        }

        exit(json_encode(array('code' => 0, 'msg' => '保存成功')));

    }

}