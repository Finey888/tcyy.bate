<?php
namespace app\tcyy\model;
use think\Db;
class Group extends Common
{
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    /**
     * @获取父级数据
     */
    public function getDataByPid($pid,$type){
        $data = $this::where(['pid'=>$pid,'status'=>1,'type'=>$type])->field('id,title')->order('sort desc,id desc')->select();
        return $data;
    }
}
