<?php


namespace app\admins\controller;

use Util\data\Sysdb;


class Roles extends BaseAdmin
{
    //主页
    public function index()
    {
        $data['Roles'] = $this->db->table('admin_groups')->lists();
        $this->assign('list', $data);
        return $this->fetch();
    }

    //添加修改角色
    public function add()
    {
        $gid = (int)input('get.gid');
        $role = $this->db->table('admin_groups')->where(array('gid' => $gid))->item();
        $role && $role['rights'] && $role['rights'] = json_decode($role['rights']);
        $this->assign('role', $role);
        $menus_list = $this->db->table('admin_menus')->where(array('status' => 0))->cates('mid');
        $menus = $this->geTreeMenuList($menus_list);
        $result = array();
        foreach ($menus as $value) {
            $value['children'] = isset($value['children']) ? $this->formatMenuList($value['children']) : false;
            $result[] = $value;
        }
        $this->assign('menus', $result);
        return $this->fetch();
    }

    //重新排列内容
    public function geTreeMenuList($items)
    {
        $tree = array();
        foreach ($items as $item) {
            if (isset($items[$item['pid']])) {
                $items[$item['pid']]['children'][] = &$items[$item['mid']];
            } else {
                $tree[] =& $items[$item['mid']];
            }
        }
        return $tree;
    }

    //排列子分类
    public function formatMenuList($items, &$res = array())
    {
        foreach ($items as $item) {
            if (!isset($item['children'])) {
                $res[] = $item;
            } else {
                $temp = $item['children'];
                unset($item['children']);
                $res[] = $item;
                $this->formatMenuList($temp, $res);
            }
        }
        return $res;
    }

    public function save()
    {
        $gid = (int)input('get.gid');
        $data['title'] = trim(input('post.title'));
        $menus = input('post.menu/a');
        if ($data['title'] == '') {
            exit(json_encode(array('code' => 1, "msg" => '请输入角色名称')));
        }
        //合并数组
        $menus && $data['rights'] = json_encode(array_keys($menus));
        if ($gid > 0) {
            $data['gid'] = $gid;
            $this->db->table('admin_groups')->update($data);
        } else {
            $this->db->table('admin_groups')->insert($data);
        }

        exit(json_encode(array('code' => 0, "msg" => '保存成功')));
    }

    //删除角色
    public function deleteRoles()
    {
        $gid = (int)input('post.gid');
        $res = $this->db->table('admin_groups')->where(array('gid' => $gid))->delete();
        if ($res) {
            exit(json_encode(array('code' => 0, 'msg' => '删除成功')));
        } else {
            exit(json_encode(array('code' => 1, 'msg' => '删除失败')));
        }
    }

}