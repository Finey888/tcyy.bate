<?php
namespace app\tcyy\model;
use think\Db;
class CasesImage extends Common
{
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    public function saveData($data){
        return $this::allowField(true)->saveAll($data);
    }
    
    
    //获取图片数据
    public function getImageAttr($value){
        if(stripos($value,"http://") == false){
            return $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$value;
        }else{
            return str_replace($_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'], '', $value);
        }
    }
    
    
}
