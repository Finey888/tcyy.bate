<?php
namespace app\index\validate;
use think\Validate;

/**
 * 需要验证的tablename和文件名和classname一致
 */
class AuthGroup extends Validate
{
    //验证规则
    protected $rule = [
	'title'  => 'require',
        'status'  => 'require',
        ];
    
    //验证失败时返回的错误信息
    protected $message = [
        'title.require' => '分组标题必填！',
        'status.require' => '状态必填',
     ];

    //场景严重设置
    protected $scene = [
	'add'   =>  ['title','status'],
        'edit'  =>  ['title','status'],
    ];
}
