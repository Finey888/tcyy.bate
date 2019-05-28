<?php
namespace app\index\validate;
use think\Validate;

/**
 * 需要验证的tablename和文件名和classname一致
 */
class Version extends Validate
{
    //验证规则
    protected $rule = [
            'username'  => 'require|unique:member|min:4|max:18|chsAlphaNum',
            'password'  => 'require|min:6',
            'rpassword'  => 'require|confirm:password',
            'nickname'=>'require|chsDash'
        ];
    
    //验证失败时返回的错误信息
    protected $message = [
            'username.unique' => '用户名已经存在',
            'username.require' => '用户名必须',
            'username.alphaNum'  => '用户名只能是汉字、字母和数字',
            'username.min' => '用户名最少4个字符',  
            'username.max' => '用户名最多18个字符',  
            'password.require' => '密码必须',
            'password.min' => '密码最小6个字符',
            'rpassword.require' => '确认密码必须',
            'rpassword.confirm' => '两次密码不一致',
            'nickname.require'  => '昵称必须',
            'nickname.chsDash'  => '昵称只能是汉字、字母、数字和下划线_及破折号-',
            
     ];
    
    //场景严重设置
    protected $scene = [
        'add'   =>  ['username','password','rpassword','nickname'],
        'edit'  =>  ['nickname']
    ];
}
