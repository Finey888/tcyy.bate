<?php
namespace app\index\validate;
use think\Validate;

/**
 * 需要验证的tablename和文件名和classname一致
 */
class BrandProductClass extends Validate
{
    //验证规则
    protected $rule = [
            'title'  => 'require',
            'brand_id'  => 'require',
        ];
    
    //验证失败时返回的错误信息
    protected $message = [
            'title.require' => '标题必须',
            'brand_id.require' => '分类必须',
            
     ];
    
    //场景严重设置
    protected $scene = [
        'add'   =>  ['title','brand_id'],
        'edit'   =>  ['title','brand_id']
    ];
}
