<?php
namespace app\tcyy\validate;
use think\Validate;

/**
 * 需要验证的tablename和文件名和classname一致
 */
class ProjectPrice extends Validate
{
    //验证规则
    protected $rule = [
            'uid'  => 'require',
            'pid'  => 'require',
            'price'  => 'require',
        ];
    
    //验证失败时返回的错误信息
    protected $message = [
            'uid.require' => '缺少用户标识',
            'pid.require' => '缺少项目',
            'price.require' => '缺少价钱',
     ];
    
    //场景严重设置
    protected $scene = [
        'add'   =>  ['uid','pid','price'],
        'edit'   =>  ['price'],
    ];
}
