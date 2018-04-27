<?php
namespace app\user\controller;
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Access-Control-Allow-Credentials: true");
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
        $uid = Session::get('user.id');
        $sid = $request->post('sid');

        // 文件及配置相关信息
        $file1id = $request->post('file1id');// 文件1id
        $file1num = empty($request->post('file1num')) ? 1 : $request->post('file1num');//文件数量,至少为1
        $file1color = empty($request->post('file1color')) ? 0 : $request->post('file1color');//0-黑白 1-彩色
        $file1style = empty($request->post('file1style')) ? 0 : $request->post('file1style');//0-单面 1-双面
        $file2id = $request->post('file2id');// 文件2id
        $file2num = empty($request->post('file2num')) ? 1 : $request->post('file2num');//文件数量,至少为1
        $file2color = empty($request->post('file2color')) ? 0 : $request->post('file2color');//0-黑白 1-彩色
        $file2style = empty($request->post('file2style')) ? 0 : $request->post('file2style');//0-单面 1-双面
        $file3id = $request->post('file3id');// 文件3id
        $file3num = empty($request->post('file3num')) ? 1 : $request->post('file3num');//文件数量,至少为1
        $file3color = empty($request->post('file3color')) ? 0 : $request->post('file3color');//0-黑白 1-彩色
        $file3style = empty($request->post('file3style')) ? 0 : $request->post('file3style');//0-单面 1-双面
        $remark = $request->post('remark');
        $status = 0;
        $createtime = date("Y-m-d H:i:s", time());
        try {
            $rel = Db::name('order')
                ->strict(false)
                ->insert([
                    'uid' => $uid,
                    'sid' => $sid,
                    'file1id' => $file1id,
                    'file1num' => $file1num,
                    'file1color' => $file1color,
                    'file1style' => $file1style,
                    'file2id' => $file2id,
                    'file2num' => $file2num,
                    'file2color' => $file2color,
                    'file2style' => $file2style,
                    'file3id' => $file3id,
                    'file3num' => $file3num,
                    'file3color' => $file3color,
                    'file3style' => $file3style,
                    'remark' => $remark,
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
