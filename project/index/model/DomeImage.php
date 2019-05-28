<?php
namespace app\index\model;
use think\Db;
class DomeImage extends Common
{
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    public function DomeGroup()
    {
        return $this->hasOne('DomeGroup','id','group_id');
    }
    
    //数据
    public function getPageData($where=['status'=>['neq',-1]],$sort='id desc'){
   
        $data = $this::with('DomeGroup')->where($where)->order($sort)->select();

        return empty($data)?[]:$data->toArray();
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
