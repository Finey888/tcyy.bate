<?php
namespace app\index\model;
use think\Db;
class ExtendDisplay extends Common
{
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    //获取所有可用数据
    public function getAllData(){
        $data = $this::where(['status'=>1])->select();
        return $data;
    }
    
    //分页数据
    public function getPageData($where=['status'=>['neq',-1]],$sort='id desc'){
        $data = $this::field('id,title,status')->where($where)->order($sort)->select();
        return $data;
    }
    
    //修改状态
    public function setStatus($id,$status){
        $data = $this->where(['id'=>$id])->update(['status'=>$status]);
        return $data;
    }
    
    //根据ID获取数据
    public function getDataById($id){
        return $this::where(['id'=>$id])->find();
    }
    
    //保存数据
    public function saveData($data,$where=[]){
        $saveType = 'ExtendDisplay.add';
        
        if(!empty($where)){$saveType = 'ExtendDisplay.edit';}
        
        $return = $this::validate($saveType)->allowField(true)->save($data,$where);
        
        return $this::getError();
    }
    
    //根据ID删除数据删除
    public function delById($id){
        return $this::update(['id'=>$id,'status'=>-1]);
    }
    /**
     * 修改器
     * 修改器的作用是可以在数据赋值的时候自动进行转换处理
     * 方法名规则：setNameAttr() ，Name：字段名
     */
    
}
