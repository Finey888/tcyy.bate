<?php
namespace app\index\validate;
use think\Validate;

/**
 * 需要验证的tablename和文件名和classname一致
 */
class Menu extends Validate
{
    //验证规则
    protected $rule = [
	'name'  => 'require',
        'pid'  => 'require',
        'sort'  => 'require',
        ];
    
    //验证失败时返回的错误信息
    protected $message = [
        'name.require' => '菜单名称必填！',
        'pid.require' => '上级必选',
        'sort.require' => '排序必填',

     ];

    //场景严重设置
    protected $scene = [
	'add'   =>  ['name','pid','sort'],
        'edit'  =>  ['name','pid','sort'],
    ];
}
