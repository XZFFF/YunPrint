<?php
namespace app\user\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Session;

class Index extends Base
{
    public function index()
    {
        return $this->fetch();
    }

    public function login()
    {
        return $this->fetch();
    }
}
