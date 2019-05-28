<?php
namespace app\tcyy\model;
use think\Db;
class ProjectTiming extends Common
{ 
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    protected $type = [
        'times'    =>  'timestamp:Y-m-d H:i',
    ];
    /**
     * @新增数据
     */
    public function saveData($data,$where=[]){
        if(empty($where)){
            $saveType = 'ProjectTiming.add';
        }else{
            $saveType = 'ProjectTiming.edit';
        }
        
        $data = $this::validate($saveType)->allowField(true)->save($data,$where);
        
        return empty($this::getError())?['status'=>1,'data'=>$this->toArray()]:['status'=>2,'msg'=>$this::getError()];
    }
    
    /**
     * @修改预约状态
     */
    public function editStatus($id,$status){
        $data = $this::save(['status'=>$status],['id'=>$id]);
        return $data;
    }
    
    /**
     * @获取数据
     */
    public function getDataByPuid($puid){
        $data = $this::where(['puid'=>$puid])->order('id desc')->find();
        return $data->toArray();
    }
    
    /**
     * @获取数据 BYid
     */
    public function getDataById($id){
        $data = $this::where(['puid'=>$id])->order('id desc')->find();
        if(empty($data)){$data='';}else{$data = $data->toArray();};
        return $data;
    }
}
