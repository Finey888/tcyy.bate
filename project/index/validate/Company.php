<?php
namespace app\index\validate;
use think\Validate;

/**
 * 需要验证的tablename和文件名和classname一致
 */
class Company extends Validate
{
    //验证规则
    protected $rule = [
            'address'  => 'require',
            'phone'  => 'require',
            'logo'  => 'require',
            'info'  => 'require',
            'title'  => 'require',
        ];
    
    //验证失败时返回的错误信息
    protected $message = [
            'address.require' => '地址必须',
            'phone.require' => '电话必须',
            'logo.require' => 'LOGO必须',
            'info.require' => '公司简介必须',
            'title.require' => '公司名称必须',
            
     ];
    
    //场景严重设置
    protected $scene = [
        'add'   =>  ['address','phone','logo','info','title'],
        'edit'   =>  ['address','phone','logo','info','title'],
    ];
}

