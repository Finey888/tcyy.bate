<?php
namespace app\index\validate;
use think\Validate;

/**
 * 需要验证的tablename和文件名和classname一致
 */
class BrandProduct extends Validate
{
    //验证规则
    protected $rule = [
            'title'  => 'require',
            'contents'  => 'require',
            'image'  => 'require',
            'brand_id' =>'require',
            'product_class' =>'require',
        ];
    
    //验证失败时返回的错误信息
    protected $message = [
            'title.require' => '标题必须',
            'contents.require' => '内容必须',
            'image.require' => '图片必须',
            'brand_id.require' => '品牌必须',
            'product_class.require' => '分类必须',
            
            
     ];
    
    //场景严重设置
    protected $scene = [
        'add'   =>  ['title,contents,image,brand_id,product_class'],
        'edit'   =>  ['title,contents,image,brand_id,product_class']
    ];
}
