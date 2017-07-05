<?php
/**
 * Created by PhpStorm.
 * User: leif
 * Date: 13/03/2017
 * Time: 17:39
 */

namespace app\index\model;


use think\Db;
use think\Model;

/**
 * Class Admin 管理员用户 model
 * @package app\index\model
 */
class Admin extends Model
{
    /** 指定连接的数据表
     * @var string
     */
    protected $table = 'admin';

    /** 根据 email 和 password 获取对应的用户信息
     * @param $ep email 和 password 数组。
     * @return array|false|\PDOStatement|string|Model 用户信息
     */
    public static function get_user_by_EmailPassword($ep)
    {
        return Admin::where($ep)->find();
    }

    /**根据 id 获取用户信息
     * @param $id
     * @return array|false|\PDOStatement|string|Model
     */
    public static function get_user_by_id($id)
    {
        return Admin::where($id)->find();
    }

    /**获取管理员用户的数目
     * @return mixed
     */
    public static function get_admin_num()
    {
        $res = Db::query('select count(*) as count from `admin`');
        return $res[0]['count'];
    }

    /** 获取管理员用户列表
     * @return false|\PDOStatement|string|\think\Collection
     */
    public static function get_admin_list()
    {
        return Admin::where([])->select();
    }

    /** 检查 email 是否已经被注册
     * @param $email
     * @return array|false|\PDOStatement|string|Model
     */
    public static function is_email_exist($email)
    {
        $where['email'] = $email;
        return Admin::where($where)->find();
    }

    /** 注册管理员用户
     * @param $data
     * @return $this
     */
    public static function reg_admin($data)
    {
        return Admin::create($data);
    }

    /** 根据 id 删除一个管理员用户
     * @param $where
     * @return int
     */
    public static function del_one_admin_by_id($where)
    {
        return Admin::where($where)->delete();
    }

    /** 根据 id 编辑管理员的用户名密码
     * @param $where id
     * @param $data 要修改的数据
     *
     * @return $this
     */
    public static function admin_edit_UsernamePassword($where, $data)
    {
        return Admin::where($where)->update($data);
    }

    /** 修改管理员用户密码
     * @param $email
     * @param $password
     * @return int
     */
    public static function change_admin_password($email, $password)
    {
        $findWhere = [
            'email' => $email,
            'password' => $password,
        ];
        $find = Admin::where($findWhere)->find();
        if ($find) {
            //检测新密码和原密码是否相同，相同返回错误代码 2
            return 2;
        } else {
            $where['email'] = $email;
            $data['password'] = $password;
            $res = Admin::where($where)->update($data);
            if ($res) {
                return 1;
            } else {
                return 0;
            }
        }
    }

}