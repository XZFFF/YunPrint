<?php
namespace app\store\controller;
header("Access-Control-Allow-Origin:http://localhost:8000");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Access-Control-Allow-Credentials: true");
use think\Controller;
use think\Db;
use think\exception\PDOException;
use think\Request;
use think\Session;

class Order extends Base
{
    /****************** 商户发起的 ******************/

    // 订单状态： 0-待接取 1-待完成 2-待领取 3-已完成 9-已取消


    /**
     * 商户查看所有订单
     * @return \think\response\Json
     */
    public function showorder() {
        $sid = Session::get('store.id');
        try {
            $rel = Db::name('order')->where(['sid' => $sid])->select();
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
     * 商户更新订单状态
     * @param Request $request
     */
    public function changestatus(Request $request) {
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
