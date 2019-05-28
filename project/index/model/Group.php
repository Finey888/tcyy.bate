<?php
namespace app\index\model;
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
     * @获取所有可用一级分类数据
     */
    public function getAllData(){
        $data = $this::where(['status'=>1,'pid'=>0])->field('id,title')->order('sort desc,id desc')->select();
        return $data;
    }
    
    /**
     * type=1
     */
    public function getCount($where){
        $data = $this::where(['status'=>1,'pid'=>0])->field('id,title')->order('sort desc,id desc')->select();
        return $data;
    }
    
    
    public function getDataByPid($pid){
        if(empty($pid)){
            $where=['status'=>1,'type'=>1];
        }else{
            $where=['status'=>1,'pid'=>$pid,'type'=>1];
        }
        
        $data = $this::where($where)->field('id,title,status,sort')->order('id desc')->select();
        return $data;
    }
    
    public function getListByPid($pid){
        if(empty($pid)){
            $where=['status'=>['neq',-1],'type'=>1];
        }else{
            $where=['status'=>['neq',-1],'pid'=>$pid,'type'=>1];
        }
        
        $data = $this::where($where)->field('id,title,status,sort')->order('id desc')->select();
        return $data;
    }
    
    //修改状态
    public function setStatus($id,$status){
        $data = $this->where(['id'=>$id])->update(['status'=>$status]);
        return $data;
    }
    
    //添加分类
    public function saveData($data,$where=[]){
        $return = $this::allowField(true)->save($data,$where);
        return $return;
    }
    
    public function getDataById($id){
        $return = $this::where(['id'=>$id])->find();
        return $return;
    }
    
    //根据ID删除数据删除
    public function delById($id){
        return $this::update(['id'=>$id,'status'=>-1]);
    }
    
}
