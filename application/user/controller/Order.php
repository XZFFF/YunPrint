<?php
namespace app\user\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Session;

class Order extends Base
{
    /****************** 用户发起的 ******************/

    // 订单状态： 0-待接取 1-待完成 2-待领取 3-已完成 9-已取消

    /**
     * 创建新订单
     * @param Request $request
     */
    public function createorder(Request $request) {
        // 检测未登录
        if(empty(Session::has('user'))) {
            redirect('index/login');
        }
        $uid = Session::get('user.id');
        $sid = $request->post('sid');
        $filenum = $request->post('filenum');
        $filepath = $request->post('filepath');// 文件路径/文件名.文件后缀
        $status = 0;
        $createtime = date("Y-m-d H:i:s", time());
        $rel = Db::name('order')
            ->strict(false)
            ->insert([
                'uid' => $uid,
                'sid' => $sid,
                'filenum' => $filenum,
                'filepath' => $filepath,
                'status' => $status,
                'createtime' => $createtime]);
        $this->apireturn('0', '', $rel);
    }

    /**
     * 用户提交取消申请 当订单状态为 0-待接取 可以取消
     * @param Request $request
     */
    public function cancelorder(Request $request) {
        // 检测未登录
        if(empty(Session::has('user'))) {
            redirect('index/login');
        }
        $oid = $request->post('oid');
        $uid = Session::get('user.id');
        $info = Db::name('order')->where(['id'=>$oid, 'uid'=>$uid])->find();
        if (empty($info)) {
            $this->apireturn('-1', '授权错误或无指定订单信息', $info);
        }
        // 订单状态为 0-待接取
        if ($info['status'] == 0) {
            $rel = Db::name('order')->where(['id'=>$oid, 'uid'=>$uid])->update(['status' => 9]);
            $this->apireturn('0', '已成功取消订单', $rel);
        } else {
            $this->apireturn('-2', '订单当前状态无法取消', $info);
        }
    }

    /**
     * 用户领取资料并确认订单完成
     * @param Request $request
     */
    public function isfinish(Request $request) {
        // 检测未登录
        if(empty(Session::has('user'))) {
            redirect('index/login');
        }
        $oid = $request->post('oid');
//        $status = $request->post('status');
        $status = 3;
        $uid = Session::get('user.id');
        // 只有订单状态为 2-待领取 可以确认完成
        $rel = Db::name('order')->where(['id'=>$oid, 'uid'=>$uid, 'status'=>2])->update(['status' => $status]);
        if (empty($rel)) {
            $this->apireturn('-1', '授权错误或无订单状态错误', $rel);
        } else {
            $this->apireturn('0', '', $rel);
        }
    }
}
