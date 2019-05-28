<?php
namespace app\index\validate;
use think\Validate;

/**
 * 需要验证的tablename和文件名和classname一致
 */
class AuthGroupAccess extends Validate
{
    //验证规则
    protected $rule = [
	'uid'  => 'require|unique:auth_group_access',
        'group_id'  => 'require',
        ];
    
    //验证失败时返回的错误信息
    protected $message = [
        'uid.require' => '找不到用户！',
        'uid.unique'=>'用户已经设置权限角色',
        'group_id.require' => '权限角色必填！',
     ];

    //场景严重设置
    protected $scene = [
	'add'   =>  ['uid','group_id'],
        'edit'  =>  ['group_id'],
    ];
}
