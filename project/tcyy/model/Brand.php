<?php
namespace app\tcyy\model;
use think\Db;
class Brand extends Common
{
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    //获取数据
    public function getDataByPage($where=['status'=>1],$page=1,$count=20){
        $data = $this::where($where)->page($page.','.$count)->field('id,title,image')->order('sort desc,id desc')->select();

        return $data->toArray();
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
