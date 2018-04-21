<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/21
 * Time: 0:53
 */
namespace app\store\controller;

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

}
