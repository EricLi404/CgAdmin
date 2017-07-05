<?php
/**
 * Created by PhpStorm.
 * User: leif
 * Date: 13/03/2017
 * Time: 13:56
 */

namespace app\index\controller;

use \app\index\model\Admin as modelAdmin;
use \app\index\model\User as modelUser;
use \app\index\model\Cg as modelCg;

use think\Controller;
use think\Session;
use think\View;

/**
 * Class Admin 管理员用户控制器
 * @package app\index\controller
 */
class Admin extends Controller
{

    /** 管理员登录页面渲染
     * @return string
     */
    public function login()
    {
        $view = new View();
        return $view->fetch('admin_login');
    }

    /**管理员系统信息页面渲染
     * @return string
     */
    public function sysinfo()
    {
        $this->per_check();
        $view = new View();
        $u_num = Util::get_USER_num();
        $u_num['cg_check_num'] = Util::get_check_cg_num();
        $view->assign("num", $u_num);
        return $view->fetch('sysinfo');
    }

    /**管理员帮主页面渲染
     * @return string
     */
    public function help()
    {
        $this->per_check();
        $view = new View();
        return $view->fetch('help');
    }

    /**管理员权限验证，渲染页面或执行操作之前调用
     * @return bool
     */
    protected function per_check()
    {
        if (!Session::has('ext_user')) {
            $this->redirect("location:/admin_login");
            return false;
        }
        $user = json_decode(Session::get('ext_user'));
        $user = get_object_vars($user);
        $permissions = $user['permissions'];
        if ($permissions != 2) {
            if ($permissions == 1) {
                $this->redirect("location:/sadmin");
                return false;
            } elseif ($permissions == 3) {
                $this->redirect("location:/user");
                return false;
            } else {
                $this->error("权限验证错误，请重新登录。", "location:/admin_login");
            }
        } else {
            return true;
        }
        $this->error("权限验证错误，请重新登录。", "location:/admin_login");
        return false;
    }

    /**管理员默认首页渲染
     * @return string
     */
    public function index()
    {
        $this->per_check();
        $view = new View();
        return $view->fetch('admin');
    }

    /**成果分类信息维护列表页面渲染
     * @return string
     */
    public function sort_list()
    {
        $this->per_check();
        $view = new View();
        $sort = modelCg::select_all_sort();
        $view->assign("list", $sort);
        return $view->fetch('admin_sort_list');
    }

    /**新增成果分类页面渲染
     * @return string
     */
    public function add_sort()
    {
        $this->per_check();
        $view = new View();
        return $view->fetch('admin_add_sort');
    }

    /** 增加成果分类操作
     *
     */
    public function do_add_sort()
    {
        $this->per_check();
        $i = array_map('trim', input("post.", '', 'htmlspecialchars'));
        if ($i['sorta'] != "") {
            $res = modelCg::add_sort($i);
            if ($res) {
                $this->success("创建成功！", "location:/admin_add_sort");
            } else {
                $this->error("创建失败，请重试。", "location:/admin_add_sort");
            }
        } else {
            $this->error("一级分类不能为空", "location:/admin_add_sort");
        }
    }

    /**删除成果分类页面渲染
     * @param $id  要删除的成果分类 id
     * @return string
     */
    public function del_sort($id)
    {
        $this->per_check();
        $where['id'] = $id;
        $sort = modelCg::get_sort_by_id($where);
        $view = new View();
        $view->assign("sort", $sort);
        return $view->fetch('admin_del_sort');
    }

    /** 删除成果分类操作
     *
     */
    public function do_del_sort()
    {
        $this->per_check();
        $i = array_map('trim', input("post.", '', 'htmlspecialchars'));
        $id['id'] = $i['id'];
        $res = modelCg::del_sort_by_id($id);
        if ($res) {
            $this->success("删除成功", "location:/admin_sort_list");
        } else {
            $this->error("删除失败，请重试！", "location:/admin_sort_list");
        }
    }

    /**编辑成果分类页面渲染
     * @param $id 要编辑的成果分类 id
     * @return string
     */
    public function edit_sort($id)
    {
        $this->per_check();
        $where['id'] = $id;
        $sort = modelCg::get_sort_by_id($where);
        $view = new View();
        $view->assign("sort", $sort);
        return $view->fetch('admin_edit_sort');
    }

    /** 编辑成果分类操作
     *
     */
    public function do_edit_sort()
    {
        $this->per_check();
        $i = array_map('trim', input("post.", '', 'htmlspecialchars'));
        $where['id'] = $i['id'];
        if ($i['sorta'] != $i['psorta']) {
            $data['sorta'] = $i['sorta'];
        }
        if ($i['sortb1'] != $i['psortb1']) {
            $data['sortb1'] = $i['sortb1'];
        }
        if ($i['sortb2'] != $i['psortb2']) {
            $data['sortb2'] = $i['sortb2'];
        }
        if ($i['sortb3'] != $i['psortb3']) {
            $data['sortb3'] = $i['sortb3'];
        }
        if ($i['sortb4'] != $i['psortb4']) {
            $data['sortb4'] = $i['sortb4'];
        }
        if ($i['sortb5'] != $i['psortb5']) {
            $data['sortb5'] = $i['sortb5'];
        }
        if (isset($data)) {
            $res = modelCg::edit_sort_by_id($where, $data);
            if ($res) {
                $this->success("编辑成功", "location:/admin_sort_list");
            } else {
                $this->error("编辑失败,请重试", "location:/admin_sort_list");
            }
        } else {
            $this->success("您未进行修改", "location:/admin_sort_list");
        }
    }

    /**教师用户列表页面渲染
     * @return string
     */
    public function user_list()
    {
        $this->per_check();
        $list = modelUser::get_user_list();
        $view = new View();
        $view->assign("list", $list);
        return $view->fetch('admin_user_list');
    }

    /**新增教师用户页面渲染
     * @return string
     */
    public function add_user()
    {
        $this->per_check();
        $view = new View();
        return $view->fetch('admin_add_user');
    }

    /**新增教师用户操作
     *
     */
    public function do_add_user()
    {
        $this->per_check();
        $i = array_map('trim', input("post.", '', 'htmlspecialchars'));
        if ($i['email'] != "") {
            $res = modelUser::add_user($i);
            if ($res) {
                $this->success("创建成功！", "location:/admin_add_user");
            } else {
                $this->error("创建失败，请重试。", "location:/admin_add_user");
            }
        } else {
            $this->error("邮箱不能为空", "location:/admin_add_user");
        }
    }

    /**删除教师用户页面渲染
     * @param $id 要删除的教师用户 id
     * @return string
     */
    public function del_user($id)
    {
        $this->per_check();
        $where['id'] = $id;
        $sort = modelUser::get_user_by_id($where);
        $view = new View();
        $view->assign("user", $sort);
        return $view->fetch('admin_del_user');
    }

    /** 教师用户详情页面
     * @param $id 教师用户 id
     * @return string
     */
    public function user_info($id)
    {
        $this->per_check();
        $where['id'] = $id;
        $sort = modelUser::get_user_by_id($where);
        $view = new View();
        $view->assign("user", $sort);
        return $view->fetch('admin_user_info');
    }

    /** 编辑教师用户页面渲染
     * @param $id 要编辑的教师用户 id
     * @return string
     */
    public function edit_user($id)
    {
        $this->per_check();
        $where['id'] = $id;
        $user = modelUser::get_user_by_id($where);
        $view = new View();
        $view->assign("user", $user);
        return $view->fetch('admin_edit_user');
    }

    /**编辑用户操作
     *
     */
    public function do_edit_user()
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
                $this->success("编辑成功", "location:/admin_user_list");
            } else {
                $this->error("编辑失败,请重试", "location:/admin_user_list");
            }
        } else {
            $this->success("您未进行修改", "location:/admin_user_list");
        }
    }

    /**删除用户操作
     *
     */
    public function do_del_user()
    {
        $this->per_check();
        $i = array_map('trim', input("post.", '', 'htmlspecialchars'));
        $id['id'] = $i['id'];
        $res = modelUser::del_user_by_id($id);
        if ($res) {
            $this->success("删除成功", "location:/admin_user_list");
        } else {
            $this->error("删除失败，请重试！", "location:/admin_user_list");
        }
    }

    /**导入教师用户页面渲染
     * @return string
     */
    public function upload_user()
    {
        $this->per_check();
        $view = new View();
        return $view->fetch('admin_upload_user');
    }

    /**导入教师用户操作
     * csv 读取，数据编码转换，拼接
     */
    public function do_import_user()
    {
        $this->per_check();
        $filename = $_FILES['file']['tmp_name'];
        if (empty ($filename)) {
            $this->error("请选择要导入的 CSV 文件");
        }
        $handle = fopen($filename, 'r');
        $out = array();
        $n = 0;
        $data = fgetcsv($handle, 10000);
        while ($data = fgetcsv($handle, 10000)) {
            $out[$n]['email'] = iconv('gb2312', 'utf-8', $data[0]);
            $out[$n]['username'] = iconv('gb2312', 'utf-8', $data[1]);
            $out[$n]['school'] = iconv('gb2312', 'utf-8', $data[2]);
            $out[$n]['college'] = iconv('gb2312', 'utf-8', $data[3]);
            $out[$n]['direction'] = iconv('gb2312', 'utf-8', $data[4]);
            $out[$n]['resume'] = iconv('gb2312', 'utf-8', $data[5]);
            $all_email[$n] = iconv('gb2312', 'utf-8', $data[0]);
            $n++;
        }
        if (count($out) < 1) {
            $this->error("CSV 文件为空");
        } else {
            //TODO 将教师信息存储到数据库
            $check = modelUser::check_email_repeat($all_email);
            if ($check) {
                $this->error($check . "已注册，请更正后重新导入。");
            } else {
                $res = modelUser::import_user($out);
                $this->success("共" . count($out) . "条信息，成功导入" . $res . "条。");
            }
        }
        fclose($handle); //关闭指针
    }

    /**导出教师用户操作
     *
     */
    public function do_export_user()
    {
        $this->per_check();
        $row = modelUser::select_all_user();
        $str = "邮箱,姓名,学校,学院,研究方向,个人介绍\n";
        $str = iconv('utf-8', 'gb2312', $str);
        for ($i = 0; $i < count($row); $i++) {
            $email = iconv('utf-8', 'gb2312', $row[$i]['email']);
            $username = iconv('utf-8', 'gb2312', $row[$i]['username']);
            $school = iconv('utf-8', 'gb2312', $row[$i]['school']);
            $college = iconv('utf-8', 'gb2312', $row[$i]['college']);
            $direction = iconv('utf-8', 'gb2312', $row[$i]['direction']);
            $resume = iconv('utf-8', 'gb2312', $row[$i]['resume']);
            $str .= $email . "," . $username . "," . $school . "," . $college . "," . $direction . "," . $resume . "," . "\n";
        }
        $filename = "教师信息导出" . date('Ymd') . '.csv';
        Util::export_csv($filename, $str);
    }

    /**导出教师用户模板
     *
     */
    public function do_export_user_tpl()
    {
        $this->per_check();
        $str = "邮箱,姓名,学校,学院,研究方向,个人介绍\n";
        $str = iconv('utf-8', 'gb2312', $str);
        Util::export_csv("教师信息模板.csv", $str);
    }

    /**管理员修改密码页面渲染
     * @return string
     */
    public function change_password()
    {
        $this->per_check();
        $view = new View();
        return $view->fetch('change_password');
    }

    /**管理员修改密码操作
     *
     */
    public function do_change_password()
    {
        $this->per_check();
        $i = array_map('trim', input("post.", '', 'htmlspecialchars'));
        $newPassword1 = $i['newPassword1'];
        $newPassword2 = $i['newPassword2'];
        if ($newPassword1 != $newPassword2) {
            $this->error("两次密码输入不一致，密码修改失败，请重试。", "location:/admin_change_password");
        } elseif (strlen($newPassword1) < 6) {
            $this->error("密码格式错误(密码长度不可以小于6位)，密码修改失败，请重试。", "location:/admin_change_password");
        } else {
            $email = $i['email'];
            $newPassword = md5(sha1($newPassword1));
            $res = modelAdmin::change_admin_password($email, $newPassword);
            if ($res == 1) {
                $this->success("密码修改成功，请重新登录", "location:/do_admin_logout");
            } elseif ($res == 2) {
                $this->error("密码修改失败，新密码不能和原密码相同。", "location:/admin_change_password");
            } elseif ($res == 0) {
                $this->error("密码修改失败，请验证您的输入后重试。", "location:/admin_change_password");
            }
        }
    }

    /**新增成果页面渲染
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

    /**新增成果
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
                            $data['cg_status'] = "1";
                            $res = modelCg::cg_add($data);
                            if ($res) {
                                $this->success("录入成功");
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

    /**成果列表页面渲染
     * @return string
     */
    public function cg_list()
    {
        $this->per_check();
        $list = modelCg::get_cg_list();
        $view = new View();
        $view->assign("list", $list);
        return $view->fetch('cg_list');
    }

    /**导入成果信息页面渲染
     * @return string
     */
    public function upload_cg()
    {
        $this->per_check();
        $view = new View();
        return $view->fetch('cg_upload');
    }

    /**导入成果信息操作
     *
     */
    public function do_import_cg()
    {
        $this->per_check();
        $filename = $_FILES['file']['tmp_name'];
        if (empty ($filename)) {
            $this->error("请选择要导入的 CSV 文件");
        }
        $handle = fopen($filename, 'r');
        $out = array();
        $n = 0;
        $data = fgetcsv($handle, 10000);
        while ($data = fgetcsv($handle, 10000)) {
            $out[$n]['cg_name'] = iconv('gb2312', 'utf-8', $data[0]);
            $out[$n]['cg_owner'] = iconv('gb2312', 'utf-8', $data[1]);
            $out[$n]['cg_date'] = iconv('gb2312', 'utf-8', $data[2]);
            $out[$n]['cg_sorta'] = iconv('gb2312', 'utf-8', $data[3]);
            $out[$n]['cg_sortb'] = iconv('gb2312', 'utf-8', $data[4]);
            $out[$n]['cg_id'] = iconv('gb2312', 'utf-8', $data[5]);
            $out[$n]['cg_source'] = iconv('gb2312', 'utf-8', $data[6]);
            $out[$n]['cg_unit'] = iconv('gb2312', 'utf-8', $data[7]);
            $out[$n]['cg_fund'] = iconv('gb2312', 'utf-8', $data[8]);
            $out[$n]['cg_status'] = "1";
            $all_cg_name[$n] = iconv('gb2312', 'utf-8', $data[0]);
            $n++;
        }
        if (count($out) < 1) {
            $this->error("CSV 文件为空");
        } else {
            $check = modelCg::check_cg_repeat($all_cg_name);
            if ($check) {
                $this->error($check . "已存在，请更正后重新导入。");
            } else {
                $res = modelCg::import_cg($out);
                $this->success("共" . count($out) . "条信息，成功导入" . $res . "条。");
            }
        }
        fclose($handle); //关闭指针
    }

    /**导出成果信息操作
     *
     */
    public function do_export_cg()
    {
        $this->per_check();
        $row = modelCg::select_all_cg();
        $str = "项目名称,负责人,时间,类别一,类别二,项目编号,项目来源,承担单位,经费（万元）,附加项一,附加项二,附加项三\n";
        $str = iconv('utf-8', 'gb2312', $str);
        for ($i = 0; $i < count($row); $i++) {
            $cg_name = iconv('utf-8', 'gb2312', $row[$i]['cg_name']);
            $cg_owner = iconv('utf-8', 'gb2312', $row[$i]['cg_owner']);
            $cg_date = iconv('utf-8', 'gb2312', $row[$i]['cg_date']);
            $cg_sorta = iconv('utf-8', 'gb2312', $row[$i]['cg_sorta']);
            $cg_sortb = iconv('utf-8', 'gb2312', $row[$i]['cg_sortb']);
            $cg_id = iconv('utf-8', 'gb2312', $row[$i]['cg_id']);
            $cg_source = iconv('utf-8', 'gb2312', $row[$i]['cg_source']);
            $cg_unit = iconv('utf-8', 'gb2312', $row[$i]['cg_unit']);
            $cg_fund = iconv('utf-8', 'gb2312', $row[$i]['cg_fund']);
            $cg_addition_1 = iconv('utf-8', 'gb2312', $row[$i]['cg_addition_1']);
            $cg_addition_2 = iconv('utf-8', 'gb2312', $row[$i]['cg_addition_2']);
            $cg_addition_3 = iconv('utf-8', 'gb2312', $row[$i]['cg_addition_3']);
            $str .= $cg_name . "," . $cg_owner . "," . $cg_date . "," . $cg_sorta . "," . $cg_sortb . "," . $cg_id . "," . $cg_source . "," . $cg_unit . "," . $cg_fund . "," . $cg_addition_1 . "," . $cg_addition_2 . "," . $cg_addition_3 . "," . "\n";
        }
        $filename = "成果信息导出" . date('Ymd') . '.csv';
        Util::export_csv($filename, $str);
    }

    /**导出成果信息模板
     *
     */
    public function do_export_cg_tpl()
    {
        $this->per_check();
        $str = "项目名称,负责人,时间,类别一,类别二,项目编号,项目来源,承担单位,经费（万元）\n";
        $str = iconv('utf-8', 'gb2312', $str);
        Util::export_csv("成果信息模板.csv", $str);
    }

    /**显示成果信息详情页面
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

    /**删除成果信息操作
     * @param $id 成果 id
     * @return string
     */
    public function cg_del($id)
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
        $type = 0;
        $view = new View();
        $view->assign("type", $type);
        $view->assign("cg", $cg);
        return $view->fetch('cg_del');
    }

    /**删除成果操作
     *
     */
    public function do_cg_del()
    {
        $this->per_check();
        $i = array_map('trim', input("post.", '', 'htmlspecialchars'));
        $where['id'] = $i['id'];
        $type = $i['type'];
        $res = modelCg::del_cg_by_id($where);
        if ($res) {
            if ($type == "0") {
                $this->success("删除成功", "location:/admin_cg_list");
            } elseif ($type == "1") {
                $this->success("删除成功", "location:/admin_cg_check_list");
            } else {
                $this->error("参数错误，请重试！", "location:/admin_cg_list");
            }

        } else {
            if ($type == "0") {
                $this->error("删除失败，请重试", "location:/admin_cg_list");
            } elseif ($type == "1") {
                $this->error("删除失败，请重试", "location:/admin_cg_check_list");
            } else {
                $this->error("参数错误，请重试！", "location:/admin_cg_list");
            }
        }
    }

    /**编辑成果信息页面渲染
     * @param $id 要编辑的成果
     * @return string
     */
    public function cg_mod($id)
    {
        $this->per_check();
        $where['id'] = $id;
        $cg = modelCg::get_cg_by_id($where);
        if ($cg['cg_addition_1'] != "") {
            $t = explode("|", $cg['cg_addition_1']);
            $cg['cg_addition1_title'] = $t[0];
            $cg['cg_addition1_content'] = $t[1];
        }
        if ($cg['cg_addition_2'] != "") {
            $t = explode("|", $cg['cg_addition_2']);
            $cg['cg_addition2_title'] = $t[0];
            $cg['cg_addition2_content'] = $t[1];
        }
        if ($cg['cg_addition_3'] != "") {
            $t = explode("|", $cg['cg_addition_3']);
            $cg['cg_addition3_title'] = $t[0];
            $cg['cg_addition3_content'] = $t[1];
        }
        $view = new View();
        $sort = modelCg::select_all_sort();
        $view->assign('sort', $sort);
        $view->assign("cg", $cg);
        return $view->fetch('cg_mod');
    }

    /**编辑成果操作
     *
     */
    public function do_cg_mod()
    {
        $this->per_check();
        $i = array_map('trim', input("post.", '', 'htmlspecialchars'));
        $where['id'] = $i['pid'];
        if (($i['cg_addition1_title'] != "") && ($i['cg_addition1_content'] != "")) {
            $cg_addition_1 = $i['cg_addition1_title'] . "|" . $i['cg_addition1_content'];
        } else {
            $cg_addition_1 = "";
        }
        if (($i['cg_addition2_title'] != "") && ($i['cg_addition2_content'] != "")) {
            $cg_addition_2 = $i['cg_addition2_title'] . "|" . $i['cg_addition2_content'];
        } else {
            $cg_addition_2 = "";
        }
        if (($i['cg_addition3_title'] != "") && ($i['cg_addition3_content'] != "")) {
            $cg_addition_3 = $i['cg_addition3_title'] . "|" . $i['cg_addition3_content'];
        } else {
            $cg_addition_3 = "";
        }
        if ($i['cg_name'] != $i['pcg_name']) {
            $data['cg_name'] = $i['cg_name'];
        }
        if ($i['cg_owner'] != $i['pcg_owner']) {
            $data['cg_owner'] = $i['cg_owner'];
        }
        if ($i['cg_date'] != $i['pcg_date']) {
            $data['cg_date'] = $i['cg_date'];
        }
        if ($i['cg_sorta'] != $i['pcg_sorta']) {
            $data['cg_sorta'] = $i['cg_sorta'];
        }
        if ($i['cg_sortb'] != $i['pcg_sortb']) {
            $data['cg_sortb'] = $i['cg_sortb'];
        }
        if ($i['cg_id'] != $i['pcg_id']) {
            $data['cg_id'] = $i['cg_id'];
        }
        if ($i['cg_source'] != $i['cg_source']) {
            $data['cg_source'] = $i['cg_source'];
        }
        if ($i['cg_unit'] != $i['pcg_unit']) {
            $data['cg_unit'] = $i['cg_unit'];
        }
        if ($i['cg_fund'] != $i['pcg_fund']) {
            $data['cg_fund'] = $i['cg_fund'];
        }
        if ($i['cg_file_url'] != $i['pcg_file_url']) {
            $data['cg_file_url'] = $i['cg_file_url'];
        }
        if ($cg_addition_1 != $i['pcg_addition_1']) {
            $data['cg_addition_1'] = $cg_addition_1;
        }
        if ($cg_addition_2 != $i['pcg_addition_2']) {
            $data['cg_addition_2'] = $cg_addition_2;
        }
        if ($cg_addition_3 != $i['pcg_addition_3']) {
            $data['cg_addition_3'] = $cg_addition_3;
        }

        if (isset($data)) {
            $res = modelCg::edit_cg_by_id($where, $data);
            if ($res) {
                $this->success("编辑成功", "location:/admin_cg_list");
            } else {
                $this->error("编辑失败,请重试", "location:/admin_cg_list");
            }
        } else {
            $this->success("您未进行修改", "location:/admin_cg_list");
        }
    }

    /**待审核成果信息列表页面渲染
     * @return string
     */
    public function cg_check_list()
    {
        $this->per_check();
        $list = modelCg::get_cg_ckeck_list();
        $view = new View();
        foreach ($list as $k => $v) {
            $where['id'] = $v['cg_user'];
            $user = modelUser::get_user_by_id($where);
            $list[$k]['username'] = $user['username'];
        }
        $view->assign("list", $list);
        return $view->fetch('cg_check_list');
    }

    /** 审核成果信息详情页面渲染
     * @param $id 要审核的成果 id
     * @return string
     */
    public function cg_check($id)
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
        return $view->fetch('cg_check');
    }

    /** 审核成果操作
     *
     */
    public function do_cg_check()
    {
        $this->per_check();
        $i = array_map('trim', input("post.", '', 'htmlspecialchars'));
        $where['id'] = $i['id'];
        $res = modelCg::check_cg_2($where);
        if ($res) {
            $this->success("成功审核了 ID 为" . $i['id'] . "的成果", "location:/admin_cg_check_list");
        } else {
            $this->error("审核失败，请重试", "location:/admin_cg_check_list");
        }
    }

    /** 成果申诉信息列表
     * @return string
     */
    public function cg_report_list()
    {
        $this->per_check();
        $list = modelCg::get_cg_reports();
        $view = new View();
        foreach ($list as $k => $v) {
            if (mb_strlen($v['info'], "utf-8") > 10) {
                $list[$k]['info'] = mb_substr($v['info'], 0, 10, "utf-8") . "...";
            }
        }
        $view->assign('list', $list);
        return $view->fetch('cg_report_list');
    }

    /** 成果申诉信息详情
     * @param $id 成果 id
     * @return string
     */
    public function cg_report_info($id)
    {
        $this->per_check();
        $info = modelCg::get_cg_report_info_by_id($id);
        $view = new View();
        return $view->assign('info', $info)->fetch('cg_report_info');
    }

    /** 成果申诉信息删除
     * 已处理完，或只是删除
     * @param $id 成果 id
     */
    public function do_cg_report_del($id)
    {
        $this->per_check();
        $res = modelCg::cg_report_del_by_id($id);
        if ($res) {
            $this->success("处理成功", "location:/admin_cg_report_list");
        } else {
            $this->error("处理失败，请重试");
        }
    }
}
