<?php
namespace app\index\validate;
use think\Validate;

/**
 * 需要验证的tablename和文件名和classname一致
 */
class Brand extends Validate
{
    //验证规则
    protected $rule = [
            'title'  => 'require',
            'image'  => 'require',
            'class' =>'require',
            'type' =>'require',
        ];
    
    //验证失败时返回的错误信息
    protected $message = [
            'title.require' => '标题必须',
            'image.require' => '图片必须',
            'class.require' => '分类必须',
            'type.require' => '类型必须',
            
     ];
    
    //场景严重设置
    protected $scene = [
        'add'   =>  ['title,image,class,type'],
        'edit'   =>  ['title,image,class,type']
    ];
}
