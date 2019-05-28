<?php
namespace app\index\model;
use think\Db;
class PointConfig extends Common
{
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    //分页数据
    public function getPageData(){
   
        $data = $this::field('id,point,travel,title,status,jj,type,num')->where(['status'=>['neq',-1]])->order('id desc')->select();
        return $data;
    }
    
    //修改状态
    public function setStatus($id,$status){
        $data = $this->where(['id'=>$id])->update(['status'=>$status]);
        return $data;
    }
    
    //根据ID获取数据
    public function getDataById($id){
        return $this::where(['id'=>$id])->field('id,point,travel,title,status,jj,type,num')->find();
    }
    
    //保存数据
    public function saveData($data,$where=[]){
        $saveType = 'PointConfig.add';
        
        if(!empty($where)){$saveType = 'PointConfig.edit';}
        $return = $this::validate($saveType)->allowField(true)->save($data,$where);
        
        return $this::getError();
    }

    /**
     * 修改器
     * 修改器的作用是可以在数据赋值的时候自动进行转换处理
     * 方法名规则：setNameAttr() ，Name：字段名
     */
    
}
