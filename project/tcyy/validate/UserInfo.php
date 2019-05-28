<?php
namespace app\tcyy\validate;
use think\Validate;

/**
 * 需要验证的tablename和文件名和classname一致
 */
class UserInfo extends Validate
{
    //验证规则
    protected $rule = [
            'uid'  => 'require',
            'headurl'  => 'require',
            'nickname'=>'require'
        ];
    
    //验证失败时返回的错误信息
    protected $message = [
            'uid.require' => '用户ID必须',
            'headurl.require' => '头像必须',
            'nickname.require'  => '昵称必须'
     ];
    
    //场景严重设置
    protected $scene = [
        'addByPhone'   =>  ['uid','nickname'],
        'addByQQ'  =>  ['uid','headurl','nickname'],
        'addByWx'  =>  ['uid','headurl','nickname'],
    ];
}
