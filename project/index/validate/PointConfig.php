<?php
namespace app\index\validate;
use think\Validate;

/**
 * 需要验证的tablename和文件名和classname一致
 */
class PointConfig extends Validate
{
    //验证规则
    protected $rule = [
            'point'  => 'require',
            'travel'  => 'require',
            'title'  => 'require',
            'status' =>'require',
            'jj' =>'require',
        ];
    
    //验证失败时返回的错误信息
    protected $message = [
            'point.require' => '积分必须',
            'travel.require' => '次数必须',
            'title.require' => '标题必须',
            'status.require' => '状态必须',
            'jj.require' => '加减必须'
            
     ];
    
    //场景严重设置
    protected $scene = [
        'add'   =>  ['point,travel,title,status,jj'],
        'edit'   =>  ['point,travel,title,status,jj']
    ];
}
