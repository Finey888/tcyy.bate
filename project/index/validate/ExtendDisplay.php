<?php
namespace app\index\validate;
use think\Validate;

/**
 * 需要验证的tablename和文件名和classname一致
 */
class ExtendDisplay extends Validate
{
    //验证规则
    protected $rule = [
            'title'  => 'require',
        ];
    
    //验证失败时返回的错误信息
    protected $message = [
            'title.require' => '标题必须',
            
     ];
    
    //场景严重设置
    protected $scene = [
        'add'   =>  ['title'],
        'edit'   =>  ['title']
    ];
}
