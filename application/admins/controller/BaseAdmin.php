<?php


namespace app\admins\controller;

use \think\Controller;
use think\Request;
use Util\data\Sysdb;


class BaseAdmin extends Controller
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->user = session('admin');
        if (!$this->user) {
            header('Location:\admins.php\admins\Account\login');
            exit();
        }
        $this->db = new Sysdb();
    }


}