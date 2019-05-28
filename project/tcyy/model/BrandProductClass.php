<?php
namespace app\tcyy\model;
use think\Db;
class BrandProductClass extends Common
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
    public function getDataAll($brand_id){
        $where=[
            'status'=>1,
            'brand_id'=>$brand_id
        ];
        return $this::where($where)->field('id,title')->order('sort desc,id desc')->select();
    }
}
