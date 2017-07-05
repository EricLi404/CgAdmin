<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

Route::miss('index/util/show404');


return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]' => [
        ':id' => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],

//    默认首页:管理员 登录 页面
    "/" => "index/admin/login",
//    管理员 登录 页面
    "admin_login" => "index/admin/login",
//    管理员 登录 操作
    "do_admin_login" => "index/login/do_admin_login",
//    管理员 首页
    "admin" => "index/admin/index",
//    管理员 登出 操作
    "do_admin_logout" => "index/login/do_admin_logout",
//    管理员 系统信息 页面
    "admin_sysinfo" => "index/admin/sysinfo",
//    管理员 帮助信息 页面
    "admin_help" => "index/admin/help",
//    管理员 分类信息维护 页面
    "admin_sort_list" => "index/admin/sort_list",
//    管理员 增加分类 页面
    "admin_add_sort" => "index/admin/add_sort",
//    管理员 增加分类 操作
    "do_admin_add_sort" => "index/admin/do_add_sort",
//    管理员 删除分类 页面
    "admin_del_sort" => "index/admin/del_sort",
//    管理员 删除分类 操作
    "do_admin_del_sort" => "index/admin/do_del_sort",
//    管理员 编辑分类 页面
    "admin_edit_sort" => "index/admin/edit_sort",
//    管理员 编辑分类 操作
    "do_admin_edit_sort" => "index/admin/do_edit_sort",
//    管理员 教师列表 页面
    "admin_user_list" => "index/admin/user_list",
//    管理员 新增教师 页面
    "admin_add_user" => "index/admin/add_user",
//    管理员 新增教师 操作
    "do_admin_add_user" => "index/admin/do_add_user",
//    管理员 删除教师 页面
    "admin_del_user" => "index/admin/del_user",
//    管理员 删除教师 操作
    "do_admin_del_user" => "index/admin/do_del_user",
//    管理员 编辑教师 页面
    "admin_edit_user" => "index/admin/edit_user",
//    管理员 删除教师 操作
    "do_admin_edit_user" => "index/admin/do_edit_user",
//    管理员 导入教师 页面
    "admin_upload_user" => "index/admin/upload_user",
//    管理员 导入教师 操作
    "do_admin_upload_user" => "index/admin/do_import_user",
//    管理员 导出教师 操作
    "do_admin_export_user" => "index/admin/do_export_user",
//    管理员 修改密码 页面
    "admin_change_password" => "index/admin/change_password",
//    管理员 修改密码 操作
    "do_admin_change_password" => "index/admin/do_change_password",
//    管理员 导出教师信息模板 操作
    "do_admin_export_user_tpl" => "index/admin/do_export_user_tpl",
//    管理员 新增成果 页面
    "admin_cg_add" => "index/admin/cg_add",
//    管理员 新增成果 操作
    "do_admin_cg_add" => "index/admin/do_cg_add",
//    管理员 成果列表 页面
    "admin_cg_list" => "index/admin/cg_list",
//    管理员 导入导出成果 页面
    "admin_upload_cg" => "index/admin/upload_cg",
//    管理员 导出成果信息模板 操作
    "do_admin_export_cg_tpl" => "index/admin/do_export_cg_tpl",
//    管理员 导出成果信息 操作
    "do_admin_export_cg" => "index/admin/do_export_cg",
//    管理员 导入成果信息 操作
    "do_admin_upload_cg" => "index/admin/do_import_cg",
//    管理员 成果详情 页面
    "admin_cg_info" => "index/admin/cg_info",
//    管理员 删除成果 页面
    "admin_cg_del" => "index/admin/cg_del",
//    管理员 删除成果 操作
    "do_admin_cg_del" => "index/admin/do_cg_del",
//    管理员 修改成果 页面
    "admin_cg_mod" => "index/admin/cg_mod",
//    管理员 修改成果 操作
    "do_admin_cg_mod" => "index/admin/do_cg_mod",
//    管理员 成果审核 页面
    "admin_cg_check" => "index/admin/cg_check",
//    管理员 成果审核 操作
    "do_admin_cg_check" => "index/admin/do_cg_check",
//    管理员 成果审核列表 页面
    "admin_cg_check_list" => "index/admin/cg_check_list",
//    管理员 用户详情 页面
    "admin_user_info" => "index/admin/user_info",
//    管理员 成果申诉列表 页面
    "admin_cg_report_list"=>"index/admin/cg_report_list",
//    管理员 成果申诉详情 页面
    "admin_cg_report_info"=>"index/admin/cg_report_info",
//    管理员 删除成果申诉信息（标记为已处理） 操作
    "do_admin_cg_report_del"=>"index/admin/do_cg_report_del",


//    超级管理员 登录 页面
    "sadmin_login" => "index/sadmin/login",
//    超级管理员 登录 操作
    "do_sadmin_login" => "index/login/do_sadmin_login",
//    超级管理员 首页
    "sadmin" => "index/sadmin/index",
//    超级管理员 系统信息 页面
    "sadmin_sysinfo" => "index/sadmin/sysinfo",
//    超级管理员 帮助信息 页面
    "sadmin_help" => "index/sadmin/help",
//    超级管理员 修改密码 页面
    "sadmin_change_password" => "index/sadmin/change_password",
//    超级管理员 修改密码 操作
    "do_sadmin_change_password" => "index/sadmin/do_change_password",
//    超级管理员 登出 操作
    "do_sadmin_logout" => "index/login/do_sadmin_logout",
//    超级管理员 管理员列表 页面
    "sadmin_list" => "index/sadmin/sadmin_list",
//    超级管理员 新增管理员 页面
    "sadmin_add" => "index/sadmin/sadmin_add",
//    超级管理员 新增管理员 操作
    "do_sadmin_add_admin" => "index/sadmin/do_sadmin_add_admin",
//    超级管理员 删除管理员 页面
    "sadmin_del" => "index/sadmin/sadmin_del",
//    超级管理员 删除管理员 操作
    "do_sadmin_del_admin" => "index/sadmin/do_sadmin_del_admin",
//    超级管理员 编辑管理员 页面
    "sadmin_edit" => "index/sadmin/sadmin_edit",
//    超级管理员 删除管理员 操作
    "do_sadmin_edit_admin" => "index/sadmin/do_sadmin_edit_admin",


//    用户登录页面
    "user_login" => "index/user/login",
//    用户登录操作
    "do_user_login" => "index/login/do_user_login",
//    用户首页
    "user" => "index/user/index",
//    用户 登出 操作
    "do_user_logout" => "index/login/do_user_logout",
//    用户 我的成果 页面
    "user_my_cg" => "index/user/my_cg",
//    用户 个人信息维护 页面
    "user_info_mod" => "index/user/user_info_mod",
//    用户 成果申报 页面
    "user_cg_add" => "index/user/cg_add",
//    用户 成果申报 页面
    "do_user_cg_add" => "index/user/do_cg_add",
//    用户 成果信息查询 页面
    "user_cg_list" => "index/user/cg_list",
//    用户 成果认领 页面
    "user_cg_claim" => "index/user/cg_claim",
//    用户 成果认领列表 页面
    "user_cg_claim_list" => "index/user/cg_claim_list",
//    用户 成果认领  操作
    "do_admin_cg_claim" => "index/user/do_cg_claim",
//    用户 成果信息申诉 页面
    "user_cg_report" => "index/user/cg_report",
//    用户 个人信息维护 操作
    "do_user_info_mod" => "index/user/do_user_info_mod",
//    用户 修改密码 页面
    "user_change_password" => "index/user/change_password",
//    用户 修改密码 操作
    "do_user_change_password" => 'index/user/do_change_password',
//    用户 成果详情 页面
    "user_cg_info" => "index/user/cg_info",
//    用户 成果申诉 操作
    "do_user_cg_report" => "index/user/do_cg_report",

//    根据sorta获取sortb 操作
    "get_sortb_by_sorta" => "index/util/get_sortb_by_sorta",
//    上传 zip 附件 操作
    "upload_zip_file" => "index/util/upload_zip_file",

];
