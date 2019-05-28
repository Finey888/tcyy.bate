<?php
namespace app\tcyy\model;
use think\Db;
class AdvertColor extends Common
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
        $data = $this::where(['id'=>1])->find();
        return $data;
    }
    
}
