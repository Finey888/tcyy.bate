<?php
namespace app\tcyy\validate;
use think\Validate;

/**
 * 需要验证的tablename和文件名和classname一致
 */
class ProjectDetails extends Validate
{
    //验证规则
    protected $rule = [
            'price'  => 'require',
            'num'  => 'require',
            'pid'  => 'require',
            'group_id'=>'require',
        ];
    
    //验证失败时返回的错误信息
    protected $message = [
            'price.require' => '缺少价钱',
            'num.require' => '缺少数量',
            'pid.require' => '缺少项目ID',
            'group_id.require' => '缺少分类ID',
     ];
    
    //场景严重设置
    protected $scene = [
        'add'   =>  ['price','num','pid'],
    ];
}
