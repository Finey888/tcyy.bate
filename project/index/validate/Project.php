<?php
namespace app\index\validate;
use think\Validate;

/**
 * 需要验证的tablename和文件名和classname一致
 */
class Zlxm extends Validate
{
    //验证规则
    protected $rule = [
            'number'  => 'require',
            'title'  => 'require',
            'price'  => 'require',
            'image'  => 'require',
            'group_id'  => 'require',
            'status'  => 'require',
            'uid'  => 'require',
        ];
    
    //验证失败时返回的错误信息
    protected $message = [
            'number.require' => '缺少编号',
            'title.require' => '缺少标题',
            'price.require' => '缺少价钱',
            'image.require' => '缺少图片',
            'group_id.require' => '缺少分类',
            'status.require' => '缺少状态',
            'uid.require' => '缺少系统项目标识',
            
     ];
    
    //场景严重设置
    protected $scene = [
        'add'   =>  ['number,price,group_id,status,uid,image,title'],
        'edit'   =>  ['number,price,group_id,status,image,title'],
    ];
}
