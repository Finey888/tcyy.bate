<?php
namespace app\tcyy\model;
use think\Db;
class DomeImage extends Common
{
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    public function getImageAttr($value)
    {
        return $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$value;
    }
}
