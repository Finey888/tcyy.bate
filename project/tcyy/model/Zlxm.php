<?php
namespace app\tcyy\model;
use think\Db;
class Zlxm extends Common
{
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    /**
     * @获取图片列表
     */
    public function getDataFind(){
        $where=[
            'status'=>1
        ];
        return $this::where($where)->field('id,image,title')->find();
    }
    
    
    public function getImageAttr($value)
    {
        return $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$value;
    }
}
