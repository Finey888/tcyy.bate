<?php
namespace app\index\model;
use think\Db;

class Menu extends Common
{
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
        //TODO:自定义的初始化
        $this->D = Db::name('menu');
    }
    
    //根据PID 获取数据
    public function getMenuByPid($pid){
        return $this->D->where(['pid'=>$pid])->field('id,name,status,icon,herf,rule_name,sort')->order('sort desc')->select();
    }
    
    //修改状态
    public function setStatus($id,$status){
        $data = $this->D->where(['id'=>$id])->update(['status'=>$status]);
        return $data;
    }
    
    //根据ID获取数据
    public function getDataById($id){
        return $this->D->where(['id'=>$id])->field('id,name,status,icon,herf,rule_name,sort,pid')->find();
    }
    
    //保存数据
    public function saveData($data,$where=[]){
        $saveType = 'Menu.add';
        if(!empty($where)){$saveType = 'Menu.edit';}
        $return = $this::validate($saveType)->allowField(true)->save($data,$where);
        return $this::getError();
    }
    
    //根据ID删除数据
    public function delById($id){
        return $this->D->where(['id'=>$id])->delete();
    }
}
