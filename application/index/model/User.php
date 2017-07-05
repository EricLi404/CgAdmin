<?php
/**
 * Created by PhpStorm.
 * User: leif
 * Date: 13/03/2017
 * Time: 17:40
 */

namespace app\index\model;


use think\Db;
use think\Model;

/**
 * Class User 教师用户 model
 * @package app\index\model
 */
class User extends Model
{
    /** 指定操作的数据表
     * @var string
     */
    protected $table = 'user';

    /** 根据 email 和 password 修改教师用户的密码
     * @param $ep
     * @return array|false|\PDOStatement|string|Model
     */
    public static function get_user_by_EmailPassword($ep)
    {
        return User::where($ep)->find();
    }

    /**获取教师用户的数量
     * @return mixed
     */
    public static function get_user_num()
    {
        $res = Db::query('select count(*) as count from `user`');
        return $res[0]['count'];
    }

    /** 获取教师用户的列表
     * @return false|\PDOStatement|string|\think\Collection
     */
    public static function get_user_list()
    {
        return User::where([])->select();
    }

    /** 新增教师用户
     * @param $data
     * @return int|string
     */
    public static function add_user($data)
    {
        return User::insert($data);
    }

    /** 根据 id 获取教师用户 id
     * @param $where
     * @return array|false|\PDOStatement|string|Model
     */
    public static function get_user_by_id($where)
    {
        return User::where($where)->find();
    }

    /** 根据 id 修改教师用户的信息
     * @param $where 教师用户id
     * @param $data 要修改的信息
     * @return $this
     */
    public static function edit_user_by_id($where, $data)
    {
        return User::where($where)->update($data);
    }


    /** 删除指定 id 的教师用户
     * @param $id
     * @return int
     */
    public static function del_user_by_id($id)
    {
        return User::where($id)->delete();
    }

    /** 插入全部教师用户信息数组
     * @param $data 教师用户数组
     * @return int|string
     */
    public static function import_user($data)
    {
        return User::insertAll($data);
    }

    /** 根据教师用户 email 获取其 id
     * @param $email
     * @return array|false|\PDOStatement|string|Model
     */
    public static function get_id_by_email($email)
    {
        $where['email'] = $email;
        return User::where($where)->find();
    }

    /** email 查重，注册教师用户时，检查其 email 是否已注册系统
     * @param $es
     * @return bool
     */
    public static function check_email_repeat($es)
    {
        for ($i = 0; $i < count($es); $i++) {
            if (self::get_id_by_email($es[$i])) {
                return $es[$i];
            }
        }
        return false;
    }

    /** 获取指定字段的教师用户列表
     * @return false|\PDOStatement|string|\think\Collection
     */
    public static function select_all_user()
    {
        return User::field('email,username,school,college,direction,resume')->where([])->select();
    }

    /** 根据 email 修改教师用户密码
     * @param $email
     * @param $password
     * @return int
     */
    public static function change_user_password($email, $password)
    {
        $findWhere = [
            'email' => $email,
            'password' => $password,
        ];
        $find = User::where($findWhere)->find();
        if ($find) {
            //检测新密码和原密码是否相同，相同返回错误代码 2
            return 2;
        } else {
            $where['email'] = $email;
            $data['password'] = $password;
            $res = User::where($where)->update($data);
            if ($res) {
                return 1;
            } else {
                return 0;
            }
        }
    }
}