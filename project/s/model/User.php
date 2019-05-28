<?php
namespace app\s\model;
use think\Db;
class User extends Common
{
    //新增时自动写入配置字段
    protected $insert = ['reg_ip','regtime']; 
    
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    public function AuthGroup()
    {
        return $this->hasOne('UserInfo','uid');
    }
    
    
    //根据ID获取数据
    public function getDataById($id){
        return $this::with('AuthGroup')->where(['id'=>$id])->find();
    }
    
    /**
     * 修改器
     * 修改器的作用是可以在数据赋值的时候自动进行转换处理
     * 方法名规则：setNameAttr() ，Name：字段名
     */
}
