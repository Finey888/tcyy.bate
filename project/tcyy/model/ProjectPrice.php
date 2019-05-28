<?php
namespace app\tcyy\model;
use think\Db;
class ProjectPrice extends Common
{ 
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    /**
     * @新增数据
     */
    public function saveData($data,$where=[]){
        if(empty($where)){
            $saveType = 'ProjectPrice.add';
        }else{
            $saveType = 'ProjectPrice.edit';
        }
        
        $data = $this::validate($saveType)->allowField(true)->save($data,$where);
        
        return empty($this::getError())?['status'=>1,'data'=>$this->toArray()]:['status'=>2,'msg'=>$this::getError()];
    }
    
    /**
     * @获取数据
     */
    public function getDataByUidPid($pid,$uid){
        $data = $this::where(['pid'=>$pid,'uid'=>$uid])->find();
        return $data;
    }
}
