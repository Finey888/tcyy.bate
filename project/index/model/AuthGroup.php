<?php
namespace app\index\model;
use think\Db;

class AuthGroup extends Common
{
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
        //TODO:自定义的初始化
        $this->D = Db::name('auth_group');
    }
    
    //分页数据
    public function getPageData($page,$count=10,$where=[]){
        return $this->D->where($where)->field('id,title,status')->page($page.','.$count)->select();
    }
    
    //获取标题
    public function getColumn(){
        return $this->D->where(['status'=>1])->column('id,title','id');
    }


    //获取所有数据
    public function getDataAll(){
        return $this->D->where(['status'=>1])->field('id,title,status')->select();
    }
    
    //根据PID 获取数据
    public function getDataById($id){
        return $this->D->where(['id'=>$id])->field('id,title,status,rules')->find();
    }
    
    //获取总条数
    public function getCount($where=[]){
        return $this->D->where($where)->count();
    }
    
    //修改状态
    public function setStatus($id,$status){
        $data = $this->D->where(['id'=>$id])->update(['status'=>$status]);
        return $data;
    }
    
    //保存数据
    public function saveData($data,$where=[]){
        $saveType = 'Auth_group.add';
        if(!empty($where)){$saveType = 'Auth_group.edit';}
        $return = $this::validate($saveType)->allowField(true)->save($data,$where);
        return $this::getError();
    }
    
    //根据ID删除数据
    public function delById($id){
        return $this->D->where(['id'=>$id])->delete();
    }
}
