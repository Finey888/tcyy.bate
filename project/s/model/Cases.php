<?php
namespace app\s\model;
use think\Db;
class Cases extends Common
{
    protected $type = [
        'creatime'    =>  'timestamp:Y-m-d H:i:s',
    ];
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    public function UserInfo()
    {
        return $this->hasOne('UserInfo','uid','uid');
    }
    
    public function CasesFollow()
    {
        return $this->hasOne('CasesFollow','touid','uid');
    }
    
    public function CasesImage()
    {
        return $this->hasMany('CasesImage','case_id');
    }
    
    public function getDataById($id){
        $where=['id'=>$id,'status'=>1];
        $data = $this::with(['UserInfo'=>function($query){
                    $query->field('id,uid,nickname,headurl');
                },'CasesImage'=>function($query){
                    $query->field('id,case_id,image');
                }])
                ->where($where)
                ->find();
                
        return empty($data)?[]:$data->toArray();
    }
    
    public function getDataByIDR($id,$uid){

        $where=[];
        $where = ['id'=>$id,'uid'=>$uid,'status'=>1];
        
        $data = $this::with(['UserInfo'=>function($query){
                    $query->field('id,uid,nickname,headurl');
                },'CasesFollow'=>function($query)use($uid){
                    $query->where(['uid'=>$uid])->field('id,touid,status');
                },'CasesImage'=>function($query){
                    $query->field('id,case_id,image');
                }])
                ->where($where)
                ->find();
				
        return empty($data)?[]:$data->toArray();
    }
}
