<?php
namespace app\tcyy\validate;
use think\Validate;

/**
 * 需要验证的tablename和文件名和classname一致
 */
class Sendmsg extends Validate
{
    //验证规则
    protected $rule = [
            'phone'  => 'require',
            'code'  => 'require',
        ];
    
    //验证失败时返回的错误信息
    protected $message = [
            'phone.require' => '电话必须',
            'code.require' => '未生成验证码',
            
     ];
    
    //场景严重设置
    protected $scene = [
        'add'   =>  ['phone','code'],
    ];
}
