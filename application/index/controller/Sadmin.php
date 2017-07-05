<?php
/**
 * Created by PhpStorm.
 * User: leif
 * Date: 13/03/2017
 * Time: 13:57
 */

namespace app\index\controller;


use \app\index\model\Sadmin as modelSadmin;
use \app\index\model\Admin as modelAdmin;

use think\Controller;
use think\Session;
use think\View;

/** 超级管理员控制器
 * Class Sadmin
 * @package app\index\controller
 */
class Sadmin extends Controller
{
    /**登录
     * @return string
     */
    public function login()
    {
        $view = new View();
        return $view->fetch('sadmin_login');
    }

    /**首页渲染，默认页面为 sadmin
     * @return string
     */
    public function index()
    {
        $this->per_check();
        $view = new View();
        return $view->fetch('sadmin');
    }

    /**权限校验
     *  每个操作或者页面渲染之前，都要校验
     * @return bool
     */
    protected function per_check()
    {
        if (!Session::has('ext_user')) {
            $this->redirect("location:/sadmin_login");
            return false;
        }
        $user = json_decode(Session::get('ext_user'));
        $user = get_object_vars($user);
        $permissions = $user['permissions'];
        if ($permissions != 1) {
            if ($permissions == 2) {
                $this->redirect("location:/admin");
                return false;
            } elseif ($permissions == 3) {
                $this->redirect("location:/user");
                return false;
            } else {
                $this->error("权限验证错误，请重新登录。", "location:/sadmin_login");
            }
        } else {
            return true;
        }
        $this->error("权限验证错误，请重新登录。", "location:/sadmin_login");
        return false;
    }

    /**系统信息显示页面渲染
     * @return string
     */
    public function sysinfo()
    {
        $this->per_check();
        $view = new View();
        $u_num = Util::get_USER_num();
        $view->assign("num", $u_num);
        return $view->fetch('sysinfo');
    }

    /**帮助信息页面渲染
     * @return string
     */
    public function help()
    {
        $this->per_check();
        $view = new View();
        return $view->fetch('help');
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
            $this->error("两次密码输入不一致，密码修改失败，请重试。", "location:/sadmin_change_password");
        } elseif (strlen($newPassword1) < 6) {
            $this->error("密码格式错误(密码长度不可以小于6位)，密码修改失败，请重试。", "location:/sadmin_change_password");
        } else {
            $email = $i['email'];
            $newPassword = md5(sha1($newPassword1));
            $res = modelSadmin::change_sadmin_password($email, $newPassword);
            if ($res == 1) {
                $this->success("密码修改成功，请重新登录", "location:/do_sadmin_logout");
            } elseif ($res == 2) {
                $this->error("密码修改失败，新密码不能和原密码相同。", "location:/sadmin_change_password");
            } elseif ($res == 0) {
                $this->error("密码修改失败，请验证您的输入后重试。", "location:/sadmin_change_password");
            }
        }
    }

    /**管理员列表页面渲染
     * @return string
     */
    public function sadmin_list()
    {
        $this->per_check();
        $view = new View();
        $view->assign("list", modelAdmin::get_admin_list());
        return $view->fetch('sadmin_list');
    }

    /**新增管理员页面渲染
     * @return string
     */
    public function sadmin_add()
    {
        $this->per_check();
        $view = new View();
        return $view->fetch('sadmin_add');
    }

    /**删除管理员用户页面渲染
     * @param $id
     * @return string
     */
    public function sadmin_del($id)
    {
        $this->per_check();
        if (!$id) {
            $this->error("参数错误，请重试", "location:/sadmin_list");
        }
        $where['id'] = $id;
        $user = modelAdmin::get_user_by_id($where);
        $view = new View();
        $view->assign("user", $user);
        return $view->fetch('sadmin_del');
    }

    /**编辑管理员用户信息详情页面
     * @param $id
     * @return string
     */
    public function sadmin_edit($id)
    {
        $this->per_check();
        if (!$id) {
            $this->error("参数错误，请重试", "location:/sadmin_list");
        }
        $where['id'] = $id;
        $user = modelAdmin::get_user_by_id($where);
        $view = new View();
        $view->assign("user", $user);
        return $view->fetch('sadmin_edit');
    }

    /**新增管理员用户操作
     *
     */
    public function do_sadmin_add_admin()
    {
        $this->per_check();
        $i = array_map('trim', input("post.", '', 'htmlspecialchars'));
        $password1 = $i['password'];
        $password2 = $i['confirm_password'];
        if ($password1 != $password2) {
            $this->error("两次密码输入不一致，密码修改失败，请重试。", "location:/sadmin_add");
        } elseif (strlen($password1) < 6) {
            $this->error("密码格式错误(密码长度不可以小于6位)，创建管理员失败，请重试。", "location:/sadmin_add");
        } else {
            $email = $i['email'];
            $isExist = modelAdmin::is_email_exist($email);
            if ($isExist) {
                $this->error("当前邮箱已注册本系统，请重试。", "location:/sadmin_add");
            } else {
                $username = $i['username'];
                $password = md5(sha1($password1));
                $data = [
                    'email' => $email,
                    'username' => $username,
                    'password' => $password
                ];
                $res = modelAdmin::reg_admin($data);
                if ($res) {
                    $this->success("创建管理员成功。", "location:/sadmin_add");
                } else {
                    $this->error("创建管理员失败，请重试。", "location:/sadmin_add");
                }
            }
        }
    }

    /**删除管理员用户操作
     *
     */
    public function do_sadmin_del_admin()
    {
        $this->per_check();
        $i = array_map('trim', input("post.", '', 'htmlspecialchars'));
        $where['id'] = $i['id'];
        $res = modelAdmin::del_one_admin_by_id($where);
        if ($res) {
            $this->success("成功删除了ID为" . $i['id'] . "的管理员", "location:/sadmin_list");
        } else {
            $this->error("删除失败，请重试。", "location:/sadmin_list");
        }
    }

    /**编辑管理员用户操作
     *
     */
    public function do_sadmin_edit_admin()
    {
        $this->per_check();
        $i = array_map('trim', input("post.", '', 'htmlspecialchars'));
        $email = $i['email'];
        if (modelAdmin::is_email_exist($email)) {
            $username = $i['username'];
            $pusername = $i['pusername'];
            $password = md5(sha1($i['password']));
            $ppassword = $i['password'];
            if ($username == "") {
                if ($password == "") {
                    $this->success("未进行修改", "location:/sadmin_list");
                } else {
                    if ($password == $ppassword) {
                        $this->error("新密码不能与原密码相同", "location:/sadmin_list");
                    } else {
                        $data['password'] = $password;
                    }
                }
            } else {
                if ($username == $pusername) {
                    $this->error("新用户名不能与原用户名相同", "location:/sadmin_list");
                } else {
                    $data['username'] = $username;
                }
                if ($password == "") {
                    //TODO need not to do sth.
                } else {
                    if ($password == $ppassword) {
                        $this->error("新密码不能与原密码相同", "location:/sadmin_list");
                    } else {
                        $data['password'] = $password;
                    }
                }
            }
            $where['email'] = $email;
            $res = modelAdmin:: admin_edit_UsernamePassword($where, $data);
            if ($res) {
                $this->success("成功编辑了E-mail为" . $email . "的管理员", "location:/sadmin_list");
            } else {
                $this->error("编辑失败，请重试。", "location:/sadmin_list");
            }
        } else {
            $this->error("参数错误", "location:/sadmin_list");
        }
    }
}