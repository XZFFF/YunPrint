<?php
namespace app\user\controller;

use think\Controller;
use think\Db;
use think\exception\PDOException;
use think\Request;
use think\Session;

class Order extends Base
{
    /****************** 用户发起的 ******************/

    // 订单状态： 0-待接取 1-待完成 2-待领取 3-已完成 9-已取消

    /**
     * 查看自己的所有订单
     * @return \think\response\Json
     */
    public function  showorder() {
        // 检测未登录
        if(empty(Session::has('user'))) {
            redirect('index/login');
        }
        $uid = Session::get('user.id');
        try {
            $rel = Db::name('order')->where(['uid' => $uid])->select();
            if (!empty($rel)) {
                foreach ($rel as $key => $value) {
                    $file1id = $rel[$key]['file1id'];
                    $rel[$key]['file1info'] = Db::name('filepath')
                        ->where(['id' => $file1id])->find();
                    $file2id = $rel[$key]['file2id'];
                    $rel[$key]['file2info'] = Db::name('filepath')
                        ->where(['id' => $file2id])->find();
                    $file3id = $rel[$key]['file3id'];
                    $rel[$key]['file3info'] = Db::name('filepath')
                        ->where(['id' => $file3id])->find();
                }
            }
            return $this->apireturn('0', '获取成功', $rel);
        } catch (PDOException $e) {
            return $this->apireturn('-1', '获取失败', '');
        }
    }

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
        $file1id = $request->post('file1id');// 文件1id
        $file2id = $request->post('file2id');// 文件2id
        $file3id = $request->post('file3id');// 文件3id
        $status = 0;
        $createtime = date("Y-m-d H:i:s", time());
        try {
            $rel = Db::name('order')
                ->strict(false)
                ->insert([
                    'uid' => $uid,
                    'sid' => $sid,
                    'filenum' => $filenum,
                    'file1id' => $file1id,
                    'file2id' => $file2id,
                    'file3id' => $file3id,
                    'status' => $status,
                    'createtime' => $createtime]);
            return $this->apireturn('0', '创建成功', $rel);
        } catch (PDOException $e) {
            return $this->apireturn('-1', '创建失败', '');
        }
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
            return $this->apireturn('-1', '授权错误或无指定订单信息', $info);
        }
        // 订单状态为 0-待接取
        if ($info['status'] == 0) {
            $rel = Db::name('order')->where(['id'=>$oid, 'uid'=>$uid])->update(['status' => 9]);
            return $this->apireturn('0', '已成功取消订单', $rel);
        } else {
            return $this->apireturn('-2', '订单当前状态无法取消', $info);
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
            return $this->apireturn('-1', '授权错误或无订单状态错误', $rel);
        } else {
            return $this->apireturn('0', '订单完成', $rel);
        }
    }
}
