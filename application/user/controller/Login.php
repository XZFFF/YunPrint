<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/27
 * Time: 9:32
 */
namespace app\user\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Session;

class Login extends Base
{
    public function index()
    {
        return $this->fetch();
    }

    public function login()
    {
        return $this->fetch();
    }

    /**
     * 注册函数
     * @param Request $request
     */
    public function register(Request $request)
    {
        $username = $request->post('username');
        $password = $request->post('password');
        $realname = $request->post('realname');
        $tel = $request->post('tel');
        $time = date("Y-m-d H:i:s", time());
        $rel = Db::name('user')
            ->strict(false)
            ->insert([
                'username' => $username,
                'password' => $password,
                'realname' => $realname,
                'tel' => $tel,
                'time' => $time]);
        $this->apireturn('0', '', $rel);
    }

    /**
     * 登录判断函数
     * @param Request $request
     */
    public function oklogin(Request $request)
    {
        $username = $request->post('username');
        $password = $request->post('password');
        $rel = Db::name('user')
            ->field(['id', 'username', 'realname', 'tel', 'time'])
            ->where(['username' => $username, 'password' => $password])
            ->find();
        if (empty($rel)) {
            return $this->apireturn('-1', 'Wrong username or password.', $rel);
        } else {
            Session::set('user', $rel);
            return $this->apireturn('0', 'Success', $rel);
        }
    }

    /**
     * 判断是否登录
     * @return \think\response\Json
     */
    public function islogin()
    {
        if (Session::has('user')) {
            return $this->apireturn('0', 'Success', Session::get('user'));
        } else {
            return $this->apireturn('-1', 'No login.', empty(Session::has('user')));
        }
    }

    public function dellogin()
    {
        Session::delete('user');
    }

}