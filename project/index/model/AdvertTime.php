<?php
namespace app\index\model;
use think\Db;
class AdvertTime extends Common
{
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    //保存数据
    public function saveData($data,$where=[]){
        $return = $this::allowField(true)->save($data,$where);
        return $this::getError();
    }
    
}
