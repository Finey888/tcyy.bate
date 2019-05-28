<?php
namespace app\tcyy\model;
use think\Db;
class ExtendGroup extends Common
{
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
     //获取所有可用数据
    public function getList($pid=0){
        $data = $this::where(['status'=>1,'pid'=>$pid])->field('id,title,type,icon')->order('sort desc,id desc')->select();
        return $data;
    }
    
    
    public function getIconAttr($value)
    {
        if(empty($value)){
            return $value;
        }else{
            return $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$value;
        }
    }
}
