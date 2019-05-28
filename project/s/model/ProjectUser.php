<?php
namespace app\s\model;
use think\Db;
class ProjectUser extends Common
{
    //新增时自动写入配置字段
    protected $insert = ['createtime']; 
    protected $type = [
        'booktime'    =>  'timestamp:Y-m-d H:i',
    ];
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    public function projectTiming()
    {
        return $this->hasMany('ProjectTiming','puid');
    }
    
    public function group()
    {
        return $this->hasOne('Group','id','group_id');
    }
    
    public function projectTimingOne()
    {
        return $this->hasOne('ProjectTiming','puid');
    }
    
    /**
     * @获取详情
     */
    public function getDataDetails($id){
        $data = $this::with('projectTimingOne')->where(['id'=>$id])->find();
        if(!empty($data)){
            $data=$data->toArray();
        }
        return $data;
    }
    
}
