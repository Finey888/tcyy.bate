<?php
namespace app\index\validate;
use think\Validate;

/**
 * 需要验证的tablename和文件名和classname一致
 */
class ExtendGroup extends Validate
{
    //验证规则
    protected $rule = [
            'title'  => 'require',
            'pid' => 'require',
            'type' => 'require',
        ];
    
    //验证失败时返回的错误信息
    protected $message = [
            'title.require' => '标题必须',
            'pid.require' => '上级必须',
            'type.require' => '页面展示类型必须',
     ];
    
    //场景严重设置
    protected $scene = [
        'add'   =>  ['title,pid,type'],
        'edit'   =>  ['title,pid,type']
    ];
}
