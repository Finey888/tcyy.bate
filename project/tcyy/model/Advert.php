<?php
namespace app\tcyy\model;
use think\Db;
class Advert extends Common
{
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    /**
     * @查询启动页面图片
     */
    public function selectData(){
        $data = $this::where(['status'=>1])->field('image,title,url')->order('sort asc,id desc')->select();
        return $data;
    }
    
    public function getImageAttr($value)
    {
        return $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$value;
    }
    
}
