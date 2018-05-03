<?php
/**
 * Created by PhpStorm.
 * User: XZFFF
 * Date: 2017/7/5
 * Time: 17:00
 */

namespace app\user\controller;
header("Access-Control-Allow-Origin:http://localhost:8000");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Access-Control-Allow-Credentials: true");
use think\Db;
use think\Exception;
use think\Request;
use think\File;
use think\Session;

class Upload extends Base
{
    /**
     * 上传图片（单张）******已废弃******
     * @param Request $request
     * @return \think\response\Json
     */
    public function uploadimg(Request $request)
    {
        $user_path = ROOT_PATH . 'public' . DS . 'upload';
//        $yun_psth = ROOT_PATH . 'public' . DS . 'yun';
        $file = $request->file('image');
//        var_dump($file->getInfo('name'));exit;
        // 移动到框架应用根目录/public/ 目录下
        if (empty($file)) {
            return $this->apireturn(-1, 'Fail', array('info' => '', 'msg' => 'No file'));
        }
        $info = $file->move($user_path);
        if ($info) {
            // 成功上传后 获取上传路径信息
            $path = $user_path.str_replace("\\","/",$info->getSaveName());
            return $this->apireturn(0, 'Success', array('info' => $info, 'path' => $path));
        } else {
            // 上传失败获取错误信息
            return $this->apireturn(-2, 'Fail', array('info' => $info, 'msg' => $info->getError()));
        }
    }


    /**
     * 上传测试接口
     * @param Request $request
     */
    public function uploadtest(Request $request) {
        $file = $request->file('file');
        echo json_encode($file);
    }

    /**
     * 上传多图（多图）
     * @param Request $request
     * @return \think\response\Json
     */
    public function upload(Request $request) {
        // 获取表单上传文件
        $user_path = ROOT_PATH . 'public' . DS . 'upload';
//        $yun_psth = ROOT_PATH . 'public' . DS . 'yun';
        $files = $request->file('image');
        $rel = array();
        $i = 1;
        foreach($files as $file) {
            // 移动到框架应用根目录/public/ 目录下
            if (empty($file)) {
                continue;
            }
            $filename = $file->getInfo('name');
            $info = $file->move($user_path);
            //$info->getExtension();//文件类型 JPG
            if ($info) {
                // 成功上传后 获取上传路径信息
                $path = $user_path . str_replace("\\", "/", $info->getSaveName());
                $result = Db::name('filepath')->strict(false)
                        ->insert([
                            'filename' => $filename,
                            'savename' => $info->getSaveName(),
                            'author' => Session::get('user.realname'),
                            'time' => date("Y-m-d H:i:s", time()),
                            'status' => '0' // 0-非公开 1-公开
                            ]);
                if ($result == 1) {
                    $msg = '上传成功'; // 映射关系是否存入数据库
                } else {
                    $msg = '上传失败';
                }
                $fileid = Db::name('filepath')->getLastInsID();
            } else {
                // 上传失败获取错误信息 $info->getError()
                $fileid = '';
                $path = '';
                $msg = $info->getError(); // 错误信息
            }
            // 返回数据格式规范化
            $rel[$i]['fileid'] = $fileid;
            $rel[$i]['path'] = $path;
            $rel[$i]['msg'] = $msg;
            $rel[$i]['filename'] = $filename;
            $i = $i+1;
        }
        // 判断结果数据并返回接口
        if ($rel) {
            return $this->apireturn(0, '上传成功', $rel);
        } else {
            return $this->apireturn(-2, '上传失败', $rel);
        }
    }
}