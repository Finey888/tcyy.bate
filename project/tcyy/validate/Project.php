<?php
namespace app\tcyy\validate;
use think\Validate;

/**
 * 需要验证的tablename和文件名和classname一致
 */
class Project extends Validate
{
    //验证规则
    protected $rule = [
            'number'  => 'require',
            'price'  => 'require',
            'title'  => 'require',
            'group_id'=>'require',
            'uid'=>'require',
            'image'=>'require',
            'status'=>'require'
        ];
    
    //验证失败时返回的错误信息
    protected $message = [
            'number.require' => '缺少电话',
            'price.require' => '缺少价钱',
            'title.require' => '缺少标题',
            'group_id.require' => '缺少分类ID',
            'uid.require' => '缺少用户id',
            'image.require' => '缺少图片',
            'status.require' => '缺少状态',
     ];
    
    //场景严重设置
    protected $scene = [
        'add'   =>  ['number','price','title','group_id','uid','image','status'],
        'edit'   =>  ['id'],
    ];
}
