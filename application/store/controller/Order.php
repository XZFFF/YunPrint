<?php
namespace app\store\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Session;

class Order extends Base
{
    /****************** 商户发起的 ******************/

    // 订单状态： 0-待接取 1-待完成 2-待领取 3-已完成 9-已取消

    /**
     * 商户更新订单状态
     * @param Request $request
     */
    public function changestatus(Request $request) {
        // 检测未登录
        if(empty(Session::has('user'))) {
            redirect('index/login');
        }
        $sid = $request->post('sid');
        $status = $request->post('status');
        $uid = Session::get('store.id');
        // 订单状态为 0-待接取 1-待完成 可以更新状态
        $rel = Db::name('order')->where(['id'=>$oid, 'uid'=>$uid, 'status'=>2])->update(['status' => $status]);
        if (empty($rel)) {
            $this->apireturn('-1', '授权错误或无订单状态错误', $rel);
        } else {
            $this->apireturn('0', '', $rel);
        }
    }
}
