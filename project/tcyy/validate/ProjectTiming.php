<?php
namespace app\tcyy\validate;
use think\Validate;

/**
 * 需要验证的tablename和文件名和classname一致
 */
class ProjectTiming extends Validate
{
    //验证规则
    protected $rule = [
            'times'  => 'require',
            'puid'  => 'require',
            'status'  => 'require',
            'updatetime'=>'require',
        ];
    
    //验证失败时返回的错误信息
    protected $message = [
            'times.require' => '缺少预约时间',
            'puid.require' => '缺少患者项目',
            'status.require' => '缺少状态',
            'updatetime.require' => '缺少修改时间',
     ];
    
    //场景严重设置
    protected $scene = [
        'add'   =>  ['times','puid'],
        'edit'   =>  ['times','puid'],        
    ];
}
