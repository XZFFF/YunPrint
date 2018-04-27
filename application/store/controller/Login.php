<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/27
 * Time: 9:32
 */
namespace app\store\controller;
header("Access-Control-Allow-Origin:localhost:8000");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Access-Control-Allow-Credentials: true");
use think\Controller;
use think\Db;
use think\exception\PDOException;
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
        $storename = $request->post('storename');
        $place = $request->post('place');
        $tel = $request->post('tel');
        $qq = $request->post('qq');
        $status = 0; // 0-正常 1-休息
        $time = date("Y-m-d H:i:s", time());
        try {
            $rel = Db::name('store')
                ->strict(false)
                ->insert([
                    'username' => $username,
                    'password' => $password,
                    'storename' => $storename,
                    'tel' => $tel,
                    'qq' => $qq,
                    'place' => $place,
                    'status' => $status,
                    'time' => $time]);
            return $this->apireturn('0', '注册成功', $rel);
        } catch (PDOException $e) {
            return $this->apireturn('-1', $e->getMessage(), '');
        }
    }

    /**
     * 登录判断函数
     * @param Request $request
     */
    public function oklogin(Request $request)
    {
        $username = $request->post('username');
        $password = $request->post('password');
        $rel = Db::name('store')
            ->field(['id', 'username', 'storename', 'place', 'tel', 'qq', 'status', 'time'])
            ->where(['username' => $username, 'password' => $password])
            ->find();
        if (empty($rel)) {
            return $this->apireturn('-1', '用户名或密码错误', $rel);
        } else {
            Session::set('store', $rel);
            return $this->apireturn('0', '登录成功', $rel);
        }
    }

    /**
     * 注销登录
     */
    public function dellogin()
    {
        Session::delete('store');
    }

    /**
     * 判断是否登录
     * @return \think\response\Json
     */
//    public function islogin()
//    {
//        if (Session::has('store')) {
//            return $this->apireturn('0', 'Success', Session::get('store'));
//        } else {
//            return $this->apireturn('-1', 'No login.', empty(Session::has('store')));
//        }
//    }



}