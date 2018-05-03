<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/21
 * Time: 0:53
 */
namespace app\store\controller;
header("Access-Control-Allow-Origin:http://localhost:8000");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Access-Control-Allow-Credentials: true");
use think\Controller;
use think\Db;
use think\exception\PDOException;
use think\Request;
use think\Session;

class Mine extends Base
{
    /**
     * 修改商户信息/更新商户状态 0-正常 1-休息
     * @param Request $request
     * @return \think\response\Json
     */
    public function editinfo(Request $request) {
        $sid = Session::get('store.id');
        $place = $request->post('place');
        $tel = $request->post('tel');
        $qq = $request->post('qq');
        $status = $request->post('status');
        try {
            $rel = Db::name('store')
                ->where(['id'=>$sid])
                ->update(['place'=>$place, 'tel'=>$tel, 'qq'=>$qq, 'status'=>$status]);
            return $this->apireturn('0', '修改成功', $rel);
        } catch (PDOException $e) {
            return $this->apireturn('0', '修改失败', '');
        }
    }

    /**
     * 修改商户信息
     * @param Request $request
     * @return \think\response\Json
     */
    public function storeinfo(Request $request)
    {
        $sid = Session::get('store.id');
        try {
            $rel = Db::name('store')->where(['id' => $sid])->find();
            return $this->apireturn('0', '获取成功', $rel);
        } catch (PDOException $e) {
            return $this->apireturn('-1', '获取失败', '');
        }
    }

}

