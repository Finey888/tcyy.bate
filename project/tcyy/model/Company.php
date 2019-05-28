<?php
namespace app\tcyy\model;
use think\Db;
class Company extends Common
{
    
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    public function getDataFind(){
        $data = $this::where(['id'=>1])->find();
        return $data;
    }
    
    public function getLogoAttr($value)
    {
        return $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$value;
    }
}
