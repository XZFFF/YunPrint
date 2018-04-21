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


    //TODO 商户所有订单


    /**
     * 商户更新订单状态
     * @param Request $request
     */
    public function changestatus(Request $request) {
        // 检测未登录
        if(empty(Session::has('store'))) {
            redirect('index/login');
        }
        $oid = $request->post('oid');
        $sid = Session::get('store.id');
        $theorder = Db::name('order')->where(['id'=>$oid, 'sid'=>$sid])->find();
        $status = $theorder['status'];
        // 订单状态为 0-待接取 1-待完成 可以更新状态
        if ($status == 0 || $status == 1) {
            $rel = Db::name('order')
                ->where(['id'=>$oid, 'sid'=>$sid])
                ->setInc('status');
            if (empty($rel)) {
                return $this->apireturn('-1', '授权错误或无订单状态错误', '');
            } else {
                return $this->apireturn('0', '更新状态成功', $rel);
            }
        } else {
            return $this->apireturn('-2', '当前状态无法更新', '');
        }
    }
}
