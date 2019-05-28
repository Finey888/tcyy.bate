<?php
namespace app\tcyy\validate;
use think\Validate;

/**
 * 需要验证的tablename和文件名和classname一致
 */
class ProjectUser extends Validate
{
    //验证规则
    protected $rule = [
            'name'  => 'require',
            'sex'  => 'require',
            'age'  => 'require',
            'phone'=>'require',
            'booktime'=>'require',
            'pids'=>'require',
            'price'=>'require',
            'group_id'=>'require',
        ];
    
    //验证失败时返回的错误信息
    protected $message = [
            'name.require' => '缺少姓名',
            'sex.require' => '缺少性别',
            'age.require' => '缺少年龄',
            'phone.require' => '缺少电话',
            'booktime.require' => '缺少预约时间',
            'pids.require' => '缺少项目',
            'price.require' => '缺少价钱',
            'group_id.require' => '缺少分类',
     ];
    
    //场景严重设置
    protected $scene = [
        'add'   =>  ['name','sex','age','phone','booktime','pids','price'],
    ];
}
