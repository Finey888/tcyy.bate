<?php
namespace app\tcyy\validate;
use think\Validate;

/**
 * 需要验证的tablename和文件名和classname一致
 */
class User extends Validate
{
    //验证规则
    protected $rule = [
            'phone'  => 'require|unique:user',
            'password'  => 'require|min:6',
            'rpassword'  => 'require|confirm:password',
            'nickname'=>'require',
            'login_type'=>'require',
            'id'=>'require',
            'other_code'=> 'require|unique:user',
            'headurl'=>'require',
        ];
    
    //验证失败时返回的错误信息
    protected $message = [
            'phone.unique' => '电话已被使用',
            'phone.require' => '电话必须',
            'password.require' => '密码必须',
            'password.min' => '密码最小6个字符',
            'rpassword.require' => '确认密码必须',
            'rpassword.confirm' => '两次密码不一致',
            'nickname.require'  => '昵称必须',
            'login_type.require'  => '注册类型必须',
            'other_code.unique' => '当前账号已经存在',
            'other_code.require' => '缺少参数：code',
            'headurl.require' => '缺少用户头像',
     ];
    
    //场景严重设置
    protected $scene = [
        'addByPhone'   =>  ['phone','password','rpassword','nickname','login_type'],
        'addByQQ'  =>  ['nickname','other_code','headurl'],
        'addByWx'  =>  ['nickname','other_code','headurl'],
        'setPasswordByPhone'=>['password','rpassword']
    ];
}
