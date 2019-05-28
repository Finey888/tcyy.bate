<?php
namespace app\index\validate;
use think\Validate;

/**
 * 需要验证的tablename和文件名和classname一致
 */
class BrandSlide extends Validate
{
    //验证规则
    protected $rule = [
            'image'  => 'require',
            'title'  => 'require',
            'type'  =>'require'
        ];
    
    //验证失败时返回的错误信息
    protected $message = [
            'image.require' => '图片必须上传',
            'title.require' => '标题必须',
            'type.require' => '类型必须',
            
     ];
    
    //场景严重设置
    protected $scene = [
        'add'   =>  ['image,title,type'],
        'edit'   =>  ['image,title,type'],
    ];
}
