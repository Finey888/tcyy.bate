<?php
namespace app\s\model;
use think\Db;
class Project extends Common
{
    //新增时自动写入配置字段
    protected $insert = ['createtime','sort']; 
    
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
}
