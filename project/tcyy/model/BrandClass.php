<?php
namespace app\tcyy\model;
use think\Db;
class BrandClass extends Common
{
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    /**
     * @获取品牌分类
     */
    public function getDataAll($type){
        $where=[
            'status'=>1,
            'type'=>$type
        ];
        return $this::where($where)->field('id,title')->order('sort desc,id desc')->select();
    }
}
