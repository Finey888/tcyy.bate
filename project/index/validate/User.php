<?php
namespace app\index\validate;
use think\Validate;

/**
 * 需要验证的tablename和文件名和classname一致
 */
class User extends Validate
{
    //验证规则
    protected $rule = [
            'vip_end_date'  => 'require'
        ];
    
    //验证失败时返回的错误信息
    protected $message = [
            'vip_end_date.require' => 'VIP到期时间必须',
            
     ];
    
    //场景严重设置
    protected $scene = [
        'vipedit'   =>  ['vip_end_date'],
    ];
}
