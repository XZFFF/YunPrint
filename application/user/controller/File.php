<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/27
 * Time: 15:25
 */
namespace app\user\controller;
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Access-Control-Allow-Credentials: true");
use think\Db;
use think\exception\PDOException;
use think\Request;
use think\Session;
class File extends Base
{
    public function getmyfile() {
        $realname = Session::get('user.realname');
        try {
            $rel = Db::name('filepath')->where(['author' => $realname])->select();
            return $this->apireturn('0', '获取成功', $rel);
        } catch (PDOException $e) {
            return $this->apireturn('-1', '获取失败', '');
        }
    }

    public function getpublicfile() {
        try {
            $rel = Db::name('filepath')->where(['status' => 1])->select();
            return $this->apireturn('0', '获取成功', $rel);
        } catch (PDOException $e) {
            return $this->apireturn('-1', '获取失败', '');
        }
    }

    public function ispublic(Request $request) {
        $fid = $request->post('fid');
        // 默认非公开
        $status = empty($request->post('status'))?0:$request->post('status');
        try {
            $rel = Db::name('filepath')
                ->where(['id'=>$fid])
                ->update(['status' => $status]);
            return $this->apireturn('0', '修改成功', $rel);
        } catch (PDOException $e) {
            return $this->apireturn('-1', '修改失败', '');
        }
    }
}
