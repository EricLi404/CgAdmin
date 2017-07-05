<?php
/**
 * Created by PhpStorm.
 * User: leif
 * Date: 13/03/2017
 * Time: 14:08
 */

namespace app\index\controller;


use \app\index\model\User as modelUser;
use \app\index\model\Cg as modelCg;

use think\Controller;
use think\Session;
use think\View;

/** 教师用户控制器
 * Class User
 * @package app\index\controller
 */
class User extends Controller

{
    /**登录页面渲染
     * @return string
     */
    public function login()
    {
        $view = new View();
        return $view->fetch('user_login');
    }

    /**权限校验操作
     * @return bool
     */
    protected function per_check()
    {
        if (!Session::has('ext_user')) {
            $this->redirect("location:/user_login");
            return false;
        }
        $user = json_decode(Session::get('ext_user'));
        $user = get_object_vars($user);
        $permissions = $user['permissions'];
        if ($permissions != 3) {
            if ($permissions == 1) {
                $this->redirect("location:/sadmin");
                return false;
            } elseif ($permissions == 2) {
                $this->redirect("location:/admin");
                return false;
            } else {
                $this->error("权限验证错误，请重新登录。", "location:/user_login");
            }
        } else {
            return true;
        }
        $this->error("权限验证错误，请重新登录。", "location:/user_login");
        return false;
    }

    /**首页渲染
     * @return string
     */
    public function index()
    {
        $this->per_check();
        $view = new View();
        return $view->fetch('user');
    }

    /**我的成果页面渲染
     * @return string
     */
    public function my_cg()
    {
        $this->per_check();
        $view = new View();
        $user = session("ext_user");
        $nums['my_cg'] = Util::get_my_cg_num($user['id']);
        $nums['check'] = Util::get_my_check_cg_num($user['id']);
        $nums['claim'] = Util::get_claim_cg_num();
        $nums['all'] = Util::get_cg_num();
        $user = Session::get('ext_user');
        $list0 = modelCg::get_my_cg_list_by_uid($user['id']);
        $list2 = modelCg::get_cg_check_list_by_uid($user['id']);
        $view->assign("list0", $list0);
        $view->assign("list2", $list2);
        $view->assign("num", $nums);
        return $view->fetch('my_cg');
    }

    /**个人信息修改页面
     * @return string
     */
    public function user_info_mod()
    {
        $this->per_check();
        $view = new View();
        $user = session('ext_user');
        $view->assign("user", $user);
        return $view->fetch('user_info_mod');
    }

    /**个人信息修改操作
     *
     */
    public function do_user_info_mod()
    {
        $this->per_check();
        $i = array_map('trim', input("post.", '', 'htmlspecialchars'));
        $where['id'] = $i['id'];
        if ($i['email'] != $i['pemail']) {
            $data['email'] = $i['email'];
        }
        if ($i['username'] != $i['pusername']) {
            $data['username'] = $i['username'];
        }
        if ($i['school'] != $i['pschool']) {
            $data['school'] = $i['school'];
        }
        if ($i['college'] != $i['pcollege']) {
            $data['college'] = $i['college'];
        }
        if ($i['direction'] != $i['pdirection']) {
            $data['direction'] = $i['direction'];
        }
        if ($i['resume'] != $i['presume']) {
            $data['resume'] = $i['resume'];
        }
        if (isset($data)) {
            $res = modelUser::edit_user_by_id($where, $data);
            if ($res) {
                $user = modelUser::get_user_by_id($where);
                unset($user['password']);
                session('ext_user', $user);
                $this->success("编辑成功", "location:/user_info_mod");
            } else {
                $this->error("编辑失败,请重试", "location:/user_info_mod");
            }
        } else {
            $this->success("您未进行修改", "location:/user_info_mod");
        }
    }

    /**修改密码页面渲染
     * @return string
     */
    public function change_password()
    {
        $this->per_check();
        $view = new View();
        return $view->fetch('change_password');
    }

    /**修改密码操作
     *
     */
    public function do_change_password()
    {
        $this->per_check();
        $i = array_map('trim', input("post.", '', 'htmlspecialchars'));
        $newPassword1 = $i['newPassword1'];
        $newPassword2 = $i['newPassword2'];
        if ($newPassword1 != $newPassword2) {
            $this->error("两次密码输入不一致，密码修改失败，请重试。", "location:/user_change_password");
        } elseif (strlen($newPassword1) < 6) {
            $this->error("密码格式错误(密码长度不可以小于6位)，密码修改失败，请重试。", "location:/user_change_password");
        } else {
            $email = $i['email'];
            $newPassword = md5(sha1($newPassword1));
            $res = modelUser::change_user_password($email, $newPassword);
            if ($res == 1) {
                $this->success("密码修改成功，请重新登录", "location:/do_user_logout");
            } elseif ($res == 2) {
                $this->error("密码修改失败，新密码不能和原密码相同。", "location:/user_change_password");
            } elseif ($res == 0) {
                $this->error("密码修改失败，请验证您的输入后重试。", "location:/user_change_password");
            }
        }
    }

    /**申报成果页面渲染
     * @return string
     */
    public function cg_add()
    {
        $this->per_check();
        $view = new View();
        $sort = modelCg::select_all_sort();
        $view->assign('sort', $sort);
        return $view->fetch('cg_add');
    }

    /**申报成成果操作
     *
     */
    public function do_cg_add()
    {
        $this->per_check();
        $i = array_map('trim', input("post.", '', 'htmlspecialchars'));
        if ($i['cg_name'] != "") {
            $data['cg_name'] = $i['cg_name'];
            if ($i['cg_owner'] != "") {
                $data['cg_owner'] = $i['cg_owner'];
                if ($i['cg_date'] != "") {
                    $data['cg_date'] = $i['cg_date'];
                    if ($i['cg_sorta'] != "") {
                        $data['cg_sorta'] = $i['cg_sorta'];
                        if ($i['cg_sortb'] != "") {
                            $data['cg_sortb'] = $i['cg_sortb'];
                            if ($i['cg_id'] != "") {
                                $data['cg_id'] = $i['cg_id'];
                            }
                            if ($i['cg_source'] != "") {
                                $data['cg_source'] = $i['cg_source'];
                            }
                            if ($i['cg_unit'] != "") {
                                $data['cg_unit'] = $i['cg_unit'];
                            }
                            if ($i['cg_fund'] != "") {
                                $data['cg_fund'] = $i['cg_fund'];
                            }
                            if ($i['cg_file_url'] != "") {
                                $data['cg_file_url'] = $i['cg_file_url'];
                            }
                            if (($i['cg_addition1_title'] != "") && ($i['cg_addition1_content'] != "")) {
                                $data['cg_addition_1'] = $i['cg_addition1_title'] . "|" . $i['cg_addition1_content'];
                            }
                            if (($i['cg_addition2_title'] != "") && ($i['cg_addition2_content'] != "")) {
                                $data['cg_addition_2'] = $i['cg_addition2_title'] . "|" . $i['cg_addition2_content'];
                            }
                            if (($i['cg_addition3_title'] != "") && ($i['cg_addition3_content'] != "")) {
                                $data['cg_addition_3'] = $i['cg_addition3_title'] . "|" . $i['cg_addition3_content'];
                            }
                            $user = session("ext_user");
                            $data['cg_user'] = $user['id'];
                            $data['cg_status'] = "2";
                            $res = modelCg::cg_add($data);
                            if ($res) {
                                $this->success("已成功申报，请等待管理员审核");
                            } else {
                                $this->error("录入失败，请重试");
                            }
                        } else {
                            $this->error("类别二不能为空");
                        }
                    } else {
                        $this->error("类别一不能为空");
                    }
                } else {
                    $this->error("时间不能为空");
                }
            } else {
                $this->error("项目负责人不能为空");
            }
        } else {
            $this->error("项目名称为必填项");
        }
    }

    /** 正式成果列表页面
     * @return string
     */
    public function cg_list()
    {
        $this->per_check();
        $list = modelCg::get_cg_list_0();
        $view = new View();
        $view->assign("list", $list);
        return $view->fetch('cg_list');
    }

    /**成果信息详情页面渲染
     * @param $id 成果 id
     * @return string
     */
    public function cg_info($id)
    {
        $this->per_check();
        $where['id'] = $id;
        $cg = modelCg::get_cg_by_id($where);
        if ($cg['cg_addition_1'] != "") {
            $t = explode("|", $cg['cg_addition_1']);
            $cg['cg_addition_1_title'] = $t[0];
            $cg['cg_addition_1_content'] = $t[1];
        }
        if ($cg['cg_addition_2'] != "") {
            $t = explode("|", $cg['cg_addition_2']);
            $cg['cg_addition_2_title'] = $t[0];
            $cg['cg_addition_2_content'] = $t[1];
        }
        if ($cg['cg_addition_3'] != "") {
            $t = explode("|", $cg['cg_addition_3']);
            $cg['cg_addition_3_title'] = $t[0];
            $cg['cg_addition_3_content'] = $t[1];
        }
        $view = new View();
        $view->assign("cg", $cg);
        return $view->fetch('cg_info');
    }

    /**待认领成果列表
     * @return string
     */
    public function cg_claim_list()
    {
        $this->per_check();
        $list = modelCg::get_cg_list_1();
        $view = new View();
        $view->assign("list", $list);
        return $view->fetch('cg_claim_list');
    }

    /** 认领成果页面渲染
     * @param $id 待认领成果 id
     * @return string
     */
    public function cg_claim($id)
    {
        $this->per_check();
        $where['id'] = $id;
        $cg = modelCg::get_cg_by_id($where);
        if ($cg['cg_addition_1'] != "") {
            $t = explode("|", $cg['cg_addition_1']);
            $cg['cg_addition_1_title'] = $t[0];
            $cg['cg_addition_1_content'] = $t[1];
        }
        if ($cg['cg_addition_2'] != "") {
            $t = explode("|", $cg['cg_addition_2']);
            $cg['cg_addition_2_title'] = $t[0];
            $cg['cg_addition_2_content'] = $t[1];
        }
        if ($cg['cg_addition_3'] != "") {
            $t = explode("|", $cg['cg_addition_3']);
            $cg['cg_addition_3_title'] = $t[0];
            $cg['cg_addition_3_content'] = $t[1];
        }
        $view = new View();
        $view->assign("cg", $cg);
        return $view->fetch('cg_claim');
    }

    /**成果认领操作
     *
     */
    public function do_cg_claim()
    {
        $this->per_check();
        $i = array_map('trim', input("post.", '', 'htmlspecialchars'));
        $where['id'] = $i['id'];
        $user = session("ext_user");
        $data['cg_user'] = $user['id'];
        $data['cg_status'] = "2";
        $res = modelCg::claim_cg_1($where, $data);
        if ($res) {
            $this->success("已申请认领 ID 为" . $i['id'] . "的成果，请等待管理员审核", "location:/user_cg_claim_list");
        } else {
            $this->error("认领失败，请重试", "location:/user_cg_claim_list");
        }
    }

    /**成果申诉页面渲染
     * @return string
     */
    public function cg_report()
    {
        $this->per_check();
        $view = new View();
        return $view->fetch('cg_report');
    }

    /**成果申诉操作
     *
     */
    public function do_cg_report()
    {
        $this->per_check();
        $i = array_map('trim', input("post.", '', 'htmlspecialchars'));
        $data['cg_name'] = $i['cg_name'];
        $data['user'] = $i['user'];
        $data['contact'] = $i['contact'];
        $data['info'] = $i['info'];
        $res = modelCg::set_cg_report($data);
        if ($res) {
            $this->success("申诉信息已提交，请等待管理员处理");
        } else {
            $this->error("申诉信息提交失败，请重试");
        }
    }
}