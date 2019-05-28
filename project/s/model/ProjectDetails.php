<?php
namespace app\s\model;
use think\Db;
class ProjectDetails extends Common
{
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    /**
     * @保存数据
     * @param int $price 单价
     * @param int $num 数量
     * @param int $pid 项目产品id
     */
    public function saveAllData($data){
        $return = $this::validate("ProjectDetails.add")->allowField(true)->saveAll($data);
        return empty($this::getError())?['status'=>1,'data'=>$return]:['status'=>2,'msg'=>$this::getError()];
    }
    
    //获取数据
    public function getDataByUid($uid,$group_id,$page=1,$count=10){
        $data = $this::where("uid=-1 or uid={$uid}")->where(['status'=>1,'group_id'=>$group_id])->page($page.','.$count)->field('id,number,price,image,title')->order('sort desc,id desc')->select();
        return $data->toArray();
    }
    
    /**
     * @删除项目
     */
    public function delByIds($ids){
        return $this::where(['id'=>['in',$ids]])->delete();
    }
    
    
    public function project()
    {
        return $this->hasOne('Project','id','pid');
    }
    
    /**
     * @查询详情 BY ids
     */
    public function getDataByIds($ids){
        $data = $this::with(['project'=>function($query){
                    $query->field('id,number,title,image');
                }])->where(['id'=>['in',$ids]])->field('id,price,num,pid')->select();
        return $data->toArray();
    }
}
