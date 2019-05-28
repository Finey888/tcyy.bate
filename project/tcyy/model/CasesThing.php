<?php
namespace app\tcyy\model;
use think\Db;
class CasesThing extends Common
{
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    /**
     * @点赞
     */
    public function addThingData($uid,$case_id){
        $data = $this::save(['uid'=>$uid,'case_id'=>$case_id,'times'=>time()]);
        return $data;
    }
    
    /**
     * @获取点赞数据
     */
    public function getDataByUidAndCaseId($uid,$case_id){
        $data = $this::where(['uid'=>$uid,'case_id'=>$case_id])->find();
        return $data;
    }
    
    public function UserInfo()
    {
        return $this->hasOne('UserInfo','uid','uid');
    }
    
    public function CasesFollow()
    {
        return $this->hasOne('CasesFollow','touid','uid');
    }
    
    /**
     * @获取点赞数据
     */
    public function getDataList($uid,$case_id,$page=1,$count=10){
        $data = $this::with(['UserInfo'=>function($query){
                    $query->field('id,uid,nickname,headurl');
                },'CasesFollow'=>function($query)use($uid){
                    $query->where(['uid'=>$uid])->field('id,touid,status');
                }])->where(['case_id'=>$case_id])
                ->page($page.','.$count)
                ->select();
        return empty($data)?[]:$data->toArray();
    }
}
