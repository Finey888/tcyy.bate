<?php
namespace app\tcyy\model;
use think\Db;
class BrandSlide extends Common
{
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    /**
     * @获取幻灯片
     */
    public function getDataByType($type){
        $where=[
            'status'=>1,
            'type'=>$type
        ];
        return $this::where($where)->field('id,image,title,url')->order('sort desc,id desc')->select();
    }
    
    
    public function getImageAttr($value)
    {
        return $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$value;
    }
}
