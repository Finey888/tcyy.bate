<?php
namespace app\index\validate;
use think\Validate;

/**
 * 需要验证的tablename和文件名和classname一致
 */
class Vip extends Validate
{
    //验证规则
    protected $rule = [
            'name'  => 'require',
            'mouth'  => 'require',
            'price'  => 'require',
            'status' =>'require'
        ];
    
    //验证失败时返回的错误信息
    protected $message = [
            'name.require' => '标题必须',
            'mouth.require' => '月数必须',
            'price.require' => '价钱必须',
            'status.require' => '状态必须',
            
     ];
    
    //场景严重设置
    protected $scene = [
        'add'   =>  ['name,mouth,price,status'],
        'edit'   =>  ['name,mouth,price,status']
    ];
}
