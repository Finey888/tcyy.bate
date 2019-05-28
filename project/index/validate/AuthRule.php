<?php
namespace app\index\validate;
use think\Validate;

/**
 * 需要验证的tablename和文件名和classname一致
 */
class AuthRule extends Validate
{
    //验证规则
    protected $rule = [
	'name'  => 'require|unique:auth_rule',
        'title'  => 'require',
        'pid'  => 'require',
        'status'  => 'require',
        ];
    
    //验证失败时返回的错误信息
    protected $message = [
        'title.require' => '权限标题必填！',
        'name.require' => '权限规则必填！',
        'pid.require' => '上级必选',
        'status.require' => '状态必填',
        'name.unique'=>'权限规则已经存在'
     ];

    //场景严重设置
    protected $scene = [
	'add'   =>  ['title','name','pid','status'],
        'edit'  =>  ['title','name','pid','status'],
    ];
}
