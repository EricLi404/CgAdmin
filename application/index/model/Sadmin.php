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
 * Class Sadmin 超级管理员 model
 * @package app\index\model
 */
class Sadmin extends Model
{
    /** 指定操作的数据表
     * @var string
     */
    protected $table = 'sadmin';

    /** 根据 email 和 password 获取超级管理员用户信息
     * @param $ep email，password 数组
     * @return array|false|\PDOStatement|string|Model
     */
    public static function get_user_by_EmailPassword($ep)
    {
        return Sadmin::where($ep)->find();
    }

    /** 获取超级管理员数目
     * @return mixed
     */
    public static function get_sadmin_num()
    {
        $res = Db::query('select count(*) as count from `sadmin`');
        return $res[0]['count'];
    }

    /** 根据 email 和 password 修改超级管理员用户的密码
     * @param $email
     * @param $password
     * @return int
     */
    public static function change_sadmin_password($email, $password)
    {
        $findWhere = [
            'email' => $email,
            'password' => $password,
        ];
        $find = Sadmin::where($findWhere)->find();
        if ($find) {
            //检测新密码和原密码是否相同，相同返回错误代码 2
            return 2;
        } else {
            $where['email'] = $email;
            $data['password'] = $password;
            $res = Sadmin::where($where)->update($data);
            if ($res) {
                return 1;
            } else {
                return 0;
            }
        }
    }
}

