<?php
namespace app\index\model;
use think\Db;
class ExtendGroup extends Common
{
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
     //获取所有可用数据
    public function getAllData($pid=0){
        $data = $this::where(['status'=>1,'pid'=>$pid])->order('sort desc')->select();
        return $data;
    }
    
    
    public function getDataByType($type){
        $data = $this::where(['status'=>1,'type'=>$type])->order('sort desc')->select();
        return $data;
    }
    
    //根据ID获取数据列表
    public function getDataByIds($ids){
        if(empty($ids)){
            return [];
        }
        $data = $this::where('id','in',$ids)->order('sort desc')->select();
        return $data;
    }
    
    //分页数据
    public function getPageData($where=['status'=>['neq',-1]],$sort='id desc'){
        $data = $this::field('id,status,sort,title,type,pid,icon')->where($where)->order($sort)->select();
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
        $saveType = 'ExtendGroup.add';
        
        if(!empty($where)){$saveType = 'ExtendGroup.edit';}
        
        $return = $this::validate($saveType)->allowField(true)->save($data,$where);
        
        return $this::getError();
    }
    
    //根据ID删除数据删除
    public function delById($id){
        return $this::update(['id'=>$id,'status'=>-1]);
    }
}
