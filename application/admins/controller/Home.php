<?php


namespace app\admins\controller;


class Home extends BaseAdmin
{
    public function index()
    {
        $menu = false;
        $role = $this->db->table('admin_groups')->where(array('gid' => $this->user['gid']))->item();
        if ($role) {
            $role['rights'] = (isset($role['rights']) && $role['rights']) ? json_decode($role['rights']) : [];
        }
        if ($role['rights']) {
            $where = 'mid in(' . implode(',', $role['rights']) . ') and ishidden=0 and status=0';
            $this->db->table('admin_menus')->where($where) - cates('mid');
        }


        return $this->fetch();
    }

    public function welcome()
    {
        return $this->fetch();
    }

}