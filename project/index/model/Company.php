<?php
namespace app\index\model;
use think\Db;
class Company extends Common
{
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    //根据ID获取数据
    public function getDataById(){
        return $this::where(['id'=>1])->find();
    }
    
    //保存数据
    public function saveData($data,$where=[]){
        $saveType = 'Company.edit';
        $return = $this::validate($saveType)->allowField(true)->save($data,$where);
        return $this::getError();
    }
    
}
