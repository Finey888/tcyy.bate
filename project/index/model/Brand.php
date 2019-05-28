<?php
namespace app\index\model;
use think\Db;
class Brand extends Common
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
     * @保存数据
     */
    public function saveData($data,$where=[]){
        $saveType = 'Brand.add';
        
        if(!empty($where)){$saveType = 'Brand.edit';}
        
        $return = $this::validate($saveType)->allowField(true)->save($data,$where);
        
        return $this::getError();
    }
    
    //根据ID获取数据
    public function getDataById($id){
        return $this::where(['id'=>$id])->find();
    }
    
    //获取列表数据
    public function getListData($where=['status'=>['neq',-1]]){
    
        $data = $this::where($where)->field('id,title,image,status,sort,info,class,type')->select();

        return empty($data)?[]:$data->toArray();
    }
    
    //修改状态
    public function setStatus($id,$status){
        $data = $this->where(['id'=>$id])->update(['status'=>$status]);
        return $data;
    }
    
    //根据ID删除数据删除
    public function delById($id){
        return $this::update(['id'=>$id,'status'=>-1]);
    }


    //设置字段值
    public function setClassAttr($value){
        if(empty($value)){
            return $value;
        }else{
            return implode(',', $value);
        }
    }
    
    public function setTypeAttr($value){
        if(empty($value)){
            return $value;
        }else{
            return implode(',', $value);
        }
    }
    
    public function getClassAttr($value){
        if(empty($value)){
            return [];
        }else{
            return explode(',', $value);
        }
    }
    
    public function getTypeAttr($value){
        if(empty($value)){
            return [];
        }else{
            return explode(',', $value);
        }
    }
}
