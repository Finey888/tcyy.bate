<?php
namespace app\tcyy\validate;
use think\Validate;

/**
 * 需要验证的tablename和文件名和classname一致
 */
class Cases extends Validate
{
    //验证规则
    protected $rule = [
            'uid'  => 'require',
            'group_id'  => 'require',
            'contents'=>'require',
            'type'=>'require'
        ];
    
    //验证失败时返回的错误信息
    protected $message = [
            'uid.require' => '用户ID必须',
            'group_id.require' => '分类必须',
            'contents.require'  => '内容必须',
            'type.require'  => '发布类型必须',
     ];
    
    //场景严重设置
    protected $scene = [
        'add'   =>  ['uid','group_id','contents','type'],
    ];
}
