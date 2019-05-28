<?php
namespace app\tcyy\model;
use think\Db;
class DomeBg extends Common
{
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    /**
     * @获取演示背景图片
     */
    public function getList(){
        $data = $this::where(['status'=>1])->field('id,image,title')->order('sort desc,id desc')->select();
        return $data;
    }
    
    public function getImageAttr($value)
    {
        return $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$value;
    }
}
