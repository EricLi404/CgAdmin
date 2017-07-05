<?php
/**
 * Created by PhpStorm.
 * User: leif
 * Date: 14/03/2017
 * Time: 10:13
 */

namespace app\index\model;


use think\Db;
use think\Model;

/**
 * Class Cg 成果信息 model
 * @package app\index\model
 */
class Cg extends Model
{
    /** 指定要连接的数据表
     * @var string
     */
    protected $table = 'cg_ujn_ise';


    /** 获取成果数量
     * @return mixed
     */
    public static function get_cg_num()
    {
        $res = Db::query('select count(*) as count from `cg_ujn_ise`');
        return $res[0]['count'];
    }

    /** 根据 sql 语句获取各类型成果数量
     * @param $sql
     * @return mixed
     */
    public static function get_nums($sql)
    {
        $res = Db::query($sql);
        return $res[0]['count'];
    }

    /** 选择所有的分类信息
     * @return false|\PDOStatement|string|\think\Collection
     */
    public static function select_all_sort()
    {
        return Db::table('cg_sort')->where([])->select();
    }

    /**新增分类信息
     * @param $sort
     * @return int|string
     */
    public static function add_sort($sort)
    {
        return Db::table('cg_sort')->insert($sort);
    }

    /** 根据 id 获取相应的成果分类
     * @param $id
     * @return array|false|\PDOStatement|string|Model
     */
    public static function get_sort_by_id($id)
    {
        return Db::table('cg_sort')->where($id)->find();
    }

    /** 根据 id 删除成果分类
     * @param $id
     * @return int
     */
    public static function del_sort_by_id($id)
    {
        return Db::table('cg_sort')->where($id)->delete();
    }

    /** 根据 id 编辑成果分类信息
     * @param $where id
     * @param $data
     * @return int|string
     */
    public static function edit_sort_by_id($where, $data)
    {
        return Db::table('cg_sort')->where($where)->update($data);
    }

    /** 根据一级分类名，选择指定分类信息
     * @param $where 一级分类名
     * @return array|false|\PDOStatement|string|Model
     */
    public static function select_sortb_by_sorta($where)
    {
        return Db::table('cg_sort')->where($where)->find();
    }

    /** 新增成果信息
     * @param $data
     * @return int|string
     */
    public static function cg_add($data)
    {
        return Cg::insert($data);
    }

    /** 获取成果列表，非全部字段
     * @return mixed
     */
    public static function get_cg_list()
    {
        return Db::query('SELECT `id`,`cg_name`,`cg_owner`,`cg_date`,`cg_sorta`,`cg_sortb` FROM `cg_ujn_ise`');
    }

    /**获取所以的成果信息条目
     * @return false|\PDOStatement|string|\think\Collection
     */
    public static function select_all_cg()
    {
        return Cg::where([])->select();
    }

    /** 根据成果的名称，获取成果信息
     * @param $cg 成果名
     * @return array|false|\PDOStatement|string|Model
     */
    public static function get_cg_by_name($cg)
    {
        $where['cg_name'] = $cg;
        return Cg::where($where)->find();
    }

    /** 根据成果的 id，获取成果信息
     * @param $where 成果 id
     * @return array|false|\PDOStatement|string|Model
     */
    public static function get_cg_by_id($where)
    {
        return Cg::where($where)->find();
    }

    /** 成果查重，检查该成果数组内的项目名是否已注册
     * @param $cg 成果名数组
     * @return bool
     */
    public static function check_cg_repeat($cg)
    {
        for ($i = 0; $i < count($cg); $i++) {
            if (self::get_cg_by_name($cg[$i])) {
                return $cg[$i];
            }
        }
        return false;
    }

    /** 插入多条成果信息
     * @param $data
     * @return int|string
     */
    public static function import_cg($data)
    {
        return Cg::insertAll($data);
    }

    /** 根据 id 删除成果信息
     * @param $where
     * @return int
     */
    public static function del_cg_by_id($where)
    {
        return cg::where($where)->delete();
    }

    /** 根据 id 修改成果信息
     * @param $where 成果 id
     * @param $data 要修改的数据
     * @return $this
     */
    public static function edit_cg_by_id($where, $data)
    {
        return Cg::where($where)->update($data);
    }

    /** 获取状态为2的成果列表，指定字段
     * @return mixed
     */
    public static function get_cg_list_2()
    {
        return Db::query('SELECT `id`,`cg_name`,`cg_owner`,`cg_date`,`cg_sorta`,`cg_sortb` FROM `cg_ujn_ise` WHERE `cg_status` = 2');
    }

    /** 将指定成果的状态修改为“0”，使之变为正式成果，审核成果
     * @param $where 成果 id
     * @return $this
     */
    public static function check_cg_2($where)
    {
        $data['cg_status'] = "0";
        return Cg::where($where)->update($data);
    }

    /** 认领成果，修改 cg_status
     * @param $where 成果id
     * @param $data 成果 status
     * @return $this
     */
    public static function claim_cg_1($where, $data)
    {
        return Cg::where($where)->update($data);
    }

    /**获取状态为0的成果列表，指定字段
     * @return mixed
     */
    public static function get_cg_list_0()
    {
        return Db::query('SELECT `id`,`cg_name`,`cg_owner`,`cg_date`,`cg_sorta`,`cg_sortb` FROM `cg_ujn_ise` WHERE `cg_status` = 0');
    }

    /** 获取状态为1的成果列表，指定字段
     * @return mixed
     */
    public static function get_cg_list_1()
    {
        return Db::query('SELECT `id`,`cg_name`,`cg_owner`,`cg_date`,`cg_sorta`,`cg_sortb` FROM `cg_ujn_ise` WHERE `cg_status` = 1');
    }

    /** 获取指定 id 用户的成果信息列表，指定字段
     * @param $uid 用户 id
     * @return mixed
     */
    public static function get_my_cg_list_by_uid($uid)
    {
        $sql = "select `id`,`cg_name`,`cg_owner`,`cg_date`,`cg_sorta`,`cg_sortb` from `cg_ujn_ise` WHERE `cg_user` = " . $uid . " AND `cg_status` = 0";
        return Db::query($sql);
    }

    /** 获取指定 id 用户的 status 为 2 的成果信息列表，指定字段
     * @param $uid 用户 id
     * @return mixed
     */
    public static function get_cg_check_list_by_uid($uid)
    {
        $sql = "select `id`,`cg_name`,`cg_owner`,`cg_date`,`cg_sorta`,`cg_sortb` from `cg_ujn_ise` WHERE `cg_user` = " . $uid . " AND `cg_status` = 2";
        return Db::query($sql);
    }

    /** 获取所有 status 为 2 的成果信息列表，指定字段
     * @return mixed
     */
    public static function get_cg_ckeck_list()
    {
        return Db::query('SELECT `id`,`cg_name`,`cg_owner`,`cg_date`,`cg_sorta`,`cg_sortb`,`cg_user` FROM `cg_ujn_ise` WHERE `cg_status` = 2');
    }

    /** 写入成果申诉信息
     * @param $data 申诉信息
     * @return int|string
     */
    public static function set_cg_report($data)
    {
        return Db::table('cg_report')->insert($data);
    }

    /** 获取成果申诉信息列表
     * @return false|\PDOStatement|string|\think\Collection
     */
    public static function get_cg_reports()
    {
        return Db::table('cg_report')->where([])->select();
    }

    /** 根据 id 获取成果申诉信息详情
     * @param $id 申诉信息 id
     * @return array|false|\PDOStatement|string|Model
     */
    public static function get_cg_report_info_by_id($id)
    {
        $where['id'] = $id;
        return Db::table('cg_report')->where($where)->find();
    }

    /** 根据 id 删除成果申诉信息
     * @param $id 申诉信息 id
     * @return int
     */
    public static function cg_report_del_by_id($id)
    {
        $where['id'] = $id;
        return Db::table('cg_report')->where($where)->delete();
    }
}