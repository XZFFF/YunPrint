<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/27
 * Time: 9:32
 */
namespace app\user\controller;
header("Access-Control-Allow-Origin:http://localhost:8000");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Access-Control-Allow-Credentials: true");
use think\Controller;
use think\Db;
use think\exception\ErrorException;
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
        $realname = $request->post('realname');
        $tel = $request->post('tel');
        $time = date("Y-m-d H:i:s", time());
        try {
            $rel = Db::name('user')
                ->strict(false)
                ->insert([
                    'username' => $username,
                    'password' => $password,
                    'realname' => $realname,
                    'tel' => $tel,
                    'time' => $time]);
            return $this->apireturn('0', '注册成功', $rel);
        } catch (PDOException $e) {
            return $this->apireturn('-1', '注册失败', '');
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
        $rel = Db::name('user')
            ->field(['id', 'username', 'realname', 'tel', 'time'])
            ->where(['username' => $username, 'password' => $password])
            ->find();
        if (empty($rel)) {
            return $this->apireturn('-1', '用户名或密码错误', $rel);
        } else {
            Session::set('user', $rel);
            return $this->apireturn('0', '登录成功', $rel);
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

    /**
     * 注销登录
     */
    public function dellogin()
    {
        Session::delete('user');
    }

    /**
     * 获取用户个人信息
     * @param Request $request
     * @return \think\response\Json
     */
    public function userinfo(Request $request)
    {
        $uid = Session::get('user.id');
        try {
            $rel = Db::name('user')->where(['id' => $uid])->find();
            return $this->apireturn('0', '获取成功', $rel);
        } catch (PDOException $e) {
            return $this->apireturn('-1', '获取失败', '');
        }
    }

    /**
     * 修改用户个人信息
     * @param Request $request
     * @return \think\response\Json
     */
    public function editinfo(Request $request)
    {
        $uid = Session::get('user.id');
        $info = Db::name('user')->where(['id' => $uid])->find();
        $password = empty($request->post('password'))?$info['password']:$request->post('password');
        $realname = empty($request->post('realname'))?$info['realname']:$request->post('realname');
        $tel = empty($request->post('tel'))?$info['tel']:$request->post('tel');
        try {
            $rel = Db::name('user')->where(['id' => $uid])
                ->update(['password'=>$password, 'realname'=>$realname, 'tel'=>$tel]);
            return $this->apireturn('0', '修改成功', $rel);
        } catch (PDOException $e) {
            return $this->apireturn('-1', '修改失败', '');
        }
    }

}