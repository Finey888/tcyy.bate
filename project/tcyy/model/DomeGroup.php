<?php
namespace app\tcyy\model;
use think\Db;
class DomeGroup extends Common
{
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    public function domeImage()
    {
        return $this->hasMany('DomeImage','group_id');
    }
    
    /**
     * @获取演示分类
     */
    public function getList(){
        $data = $this::with(['domeImage'=>function($query){
                    $query->where(['status'=>1])->field('id,title,image,group_id')->order('sort desc,id desc');
                }])->where(['status'=>1])->field('id,title')->order('sort desc,id desc')->select();
        return $data;
    }
    
}
