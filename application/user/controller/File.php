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
    /**
     * 获取个人文件库
     * @return \think\response\Json
     */
    public function getmyfile() {
        $realname = Session::get('user.realname');
        try {
            $rel = Db::name('filepath')->where(['author' => $realname])->select();
            return $this->apireturn('0', '获取成功', $rel);
        } catch (PDOException $e) {
            return $this->apireturn('-1', '获取失败', '');
        }
    }

    /**
     * 获取公开文件库
     * @return \think\response\Json
     */
    public function getpublicfile() {
        try {
            $rel = Db::name('filepath')->where(['status' => 1])->select();
            return $this->apireturn('0', '获取成功', $rel);
        } catch (PDOException $e) {
            return $this->apireturn('-1', '获取失败', '');
        }
    }

    /**
     * 更新文件公开状态 0-非公开 1-公开
     * @param Request $request
     * @return \think\response\Json
     */
    public function ispublic(Request $request) {
        $realname = Session::get('user.realname');
        $fid = $request->post('fid');
        // 默认非公开
        $status = empty($request->post('status'))?0:$request->post('status');
        try {
            $rel = Db::name('filepath')
                ->where(['id'=>$fid, 'author'=>$realname])
                ->update(['status' => $status]);
            return $this->apireturn('0', '修改成功', $rel);
        } catch (PDOException $e) {
            return $this->apireturn('-1', '修改失败', '');
        }
    }
}
