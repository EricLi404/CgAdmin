<?php
/**
 * Created by PhpStorm.
 * User: leif
 * Date: 13/03/2017
 * Time: 14:08
 */

namespace app\index\controller;

use \app\index\model\Sadmin as modelSadmin;
use \app\index\model\Admin as modelAdmin;
use \app\index\model\User as modelUser;
use \app\index\model\Cg as modelCg;

use think\Controller;
use think\Request;
use think\Session;
use think\View;

/**
 * Class Util 常用功能类
 * @package app\index\controller
 */
class Util extends Controller
{
    /** 404页面渲染
     * @return string
     */
    public function show404()
    {
        $view = new View();
        return $view->fetch('404');
    }

    /** 500页面渲染
     * @return string
     */
    public function show500()
    {
        $view = new View();
        return $view->fetch('500');
    }

    /** 获取各类型用户 的数量
     * @return mixed 各用户数量数组
     */
    public static function get_USER_num()
    {
        $user['sadmin'] = modelSadmin::get_sadmin_num();
        $user['admin'] = modelAdmin::get_admin_num();
        $user['user'] = modelUser::get_user_num();
        $user['cg'] = modelCg::get_cg_num();
        return $user;
    }

    /** 权限校验
     * 没有被使用？？？？
     * @return bool
     */
    protected function per_check()
    {
        if (!Session::has('ext_user')) {
            $this->redirect("location:/admin_login");
            return false;
        }
        return true;
    }

    /** 导出 csv 文件操作
     * @param $filename 要生成的文件的文件名
     * @param $data 文件内部数据
     */
    public static function export_csv($filename, $data)
    {
        header("Content-type:text/csv");
        header("Content-Disposition:attachment;filename=" . $filename);
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        echo $data;
    }

    /** Ajax 获取子分类
     * @return \think\response\Json
     */
    public function get_sortb_by_sorta()
    {
        $this->per_check();
        if (Request::instance()->isAjax()) {
            $sorta = trim(input('post.sorta'));
            $where['sorta'] = $sorta;
            $sortb = modelCg::select_sortb_by_sorta($where);
            $sb = array();
            for ($i = 1; $i <= 5; $i++) {
                if ($sortb["sortb" . $i] != "") {
                    $sb[$i] = $sortb["sortb" . $i];
                }
            }
            $sb[0] = count($sb);
            $ret['data'] = $sb;
            $ret['status'] = 1;
            return json($ret);
        }
    }

    /** Ajax 上传 zip 压缩文件（成果信息附件）
     * @return \think\response\Json 上传结果及文件 url
     */
    public function upload_zip_file()
    {
        if (Request::instance()->isAjax()) {
            $file = request()->file('cg_file');
            if (empty($file)) {
                $ret["result"] = "1";
                $ret['info'] = "文件不能为空";
                return json($ret);
            }
            $info = $file->validate(['size' => 20000, 'ext' => 'zip'])->move(ROOT_PATH . 'public' . DS . 'uploads', true, false);
            if ($info) {
                $ret["result"] = "0";
                $url = explode('/', $info->getRealPath());
                $ret['info'] = "http://cgadmin.top/" . $url[6] . "/" . $url[7] . "/" . $url[8];
            } else {
                $ret["result"] = "2";
                $ret['info'] = $file->getError();
            }
            return json($ret);
        }
    }

    /** 获取指定用户所有的成果的数量
     * @param $uid 用户 id
     * @return mixed 成果数量
     */
    public static function get_my_cg_num($uid)
    {
        $sql = "select count(*) as count from `cg_ujn_ise` WHERE `cg_user` = " . $uid . " AND `cg_status` = 0";
        return modelCg::get_nums($sql);
    }

    /** 获取指定用户的待审核成果数量
     * @param $uid 用户 id
     * @return mixed 成果数量
     */
    public static function get_my_check_cg_num($uid)
    {
        $sql = "select count(*) as count from `cg_ujn_ise` WHERE `cg_user` = " . $uid . " AND `cg_status` = 2";
        return modelCg::get_nums($sql);
    }

    /** 获取全部待审核的成果数量
     * @return mixed 成果数量
     */
    public static function get_check_cg_num()
    {
        $sql = "select count(*) as count from `cg_ujn_ise` WHERE  `cg_status` = 2";
        return modelCg::get_nums($sql);
    }

    /** 获取待认领成果数量
     * @return mixed 成果数量
     */
    public static function get_claim_cg_num()
    {
        $sql = "select count(*) as count from `cg_ujn_ise` WHERE  `cg_status` = 1";
        return modelCg::get_nums($sql);
    }

    /** 获取全部成果的数量
     * @return mixed 成果数量
     */
    public static function get_cg_num()
    {
        return modelCg::get_cg_num();
    }
}