<?php
/**
 * Created by PhpStorm.
 * User: leif
 * Date: 13/03/2017
 * Time: 16:40
 */

namespace app\index\controller;


use think\Controller;
use \app\index\model\Sadmin as modelSadmin;
use \app\index\model\Admin as modelAdmin;
use \app\index\model\User as modelUser;

/**
 * Class Login 登录控制器
 * @package app\index\controller
 */
class Login extends Controller
{
    /** 管理员用户登录
     *
     */
    public function do_admin_login()
    {
        $i = array_map('trim', input("post.", '', 'htmlspecialchars'));
        $email = $i['email'];
        $password = $i['password'];
        $captcha = $i['captcha'];
        if (captcha_check($captcha)) {
            $ep['email'] = $email;
            $ep['password'] = md5(sha1($password));
            $user = modelAdmin::get_user_by_EmailPassword($ep);
            if ($user) {
                unset($user['password']);
                session('ext_user', $user);
                $this->success("登陆成功", "location:/admin");
            } else {
                $this->error("用户名或密码错误,或您选择的登录身份错误", "location:/admin_login");
            }
        } else {
            $this->error("登录失败,您输入的验证码不正确。", "location:/admin_login");
        }
    }

    /**超级管理员登录
     *
     */
    public function do_sadmin_login()
    {
        $i = array_map('trim', input("post.", '', 'htmlspecialchars'));
        $email = $i['email'];
        $password = $i['password'];
        $captcha = $i['captcha'];
        if (captcha_check($captcha)) {
            $ep['email'] = $email;
            $ep['password'] = md5(sha1($password));
            $user = modelSadmin::get_user_by_EmailPassword($ep);
            if ($user) {
                unset($user['password']);
                session('ext_user', $user);
                $this->success("登陆成功", "location:/sadmin");
            } else {
                $this->error("用户名或密码错误,或您选择的登录身份错误", "location:/sadmin_login");
            }
        } else {
            $this->error("登录失败,您输入的验证码不正确。", "location:/sadmin_login");
        }
    }

    /**教师用户登录
     *
     */
    public function do_user_login()
    {
        $i = array_map('trim', input("post.", '', 'htmlspecialchars'));
        $email = $i['email'];
        $password = $i['password'];
        $captcha = $i['captcha'];
        if (captcha_check($captcha)) {
            $ep['email'] = $email;
            $ep['password'] = md5(sha1($password));
            $user = modelUser::get_user_by_EmailPassword($ep);
            if ($user) {
                unset($user['password']);
                session('ext_user', $user);
                $this->success("登陆成功", "location:/user");
            } else {
                $this->error("用户名或密码错误,或您选择的登录身份错误", "location:/user_login");
            }
        } else {
            $this->error("登录失败,您输入的验证码不正确。", "location:/user_login");
        }
    }

    /**管理员用户登出
     *
     */
    public function do_admin_logout()
    {
        $logout = session('ext_user', NULL);
        if ($logout) {
            $this->success("退出登录成功。", "location:/admin_login");
        } else {
            $this->success("退出登录成功。", "location:/admin_login");
        }
    }

    /**超级管理员用户登出
     *
     */
    public function do_sadmin_logout()
    {
        $logout = session('ext_user', NULL);
        if ($logout) {
            $this->success("退出登录成功。", "location:/sadmin_login");
        } else {
            $this->success("退出登录成功。", "location:/sadmin_login");
        }
    }

    /**教师用户登出
     *
     */
    public function do_user_logout()
    {
        $logout = session('ext_user', NULL);
        if ($logout) {
            $this->success("退出登录成功。", "location:/user_login");
        } else {
            $this->success("退出登录成功。", "location:/user_login");
        }
    }
}