<?php
namespace app\index\model;
use think\Db;
class Picture extends Common
{
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    public function Group()
    {
        return $this->hasOne('Group','id','cid');
    }
    
    //分页数据
    public function getPageData($page=1,$count=10,$where=['status'=>['neq',-1]],$sort='id desc'){
   
        $data = $this::with('Group')->where($where)->page($page.','.$count)->order($sort)->select();

        return empty($data)?[]:$data->toArray();
    }
    
    //获取数据长度
    public function getCount($where=['status'=>['neq',-1]]){
        $data = $this::where($where)->count();
        return $data;
    }


    //修改状态
    public function setStatus($id,$status){
        $data = $this->where(['id'=>$id])->update(['status'=>$status]);
        return $data;
    }
    
    //根据ID获取数据
    public function getDataById($id){
        $data = $this::where(['id'=>$id])->find();
        return empty($data)?[]:$data->toArray();
    }
    
    //保存数据
    public function saveData($data,$where=[]){
     
        $return = $this::allowField(true)->save($data,$where);
        
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
