<?php


namespace app\admins\controller;


class Home extends BaseAdmin
{
    public function index()
    {
        return $this->fetch();
    }

    public function welcome()
    {
        return $this->fetch();
    }

}