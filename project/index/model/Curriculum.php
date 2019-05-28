<?php
namespace app\index\model;
use think\Db;
class Curriculum extends Common
{
    //新增时自动写入配置字段
    protected $insert = ['creattime']; 
    protected $type = [
        'creattime'  =>  'timestamp:Y-m-d',
    ];
    
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    public function Group(){
        return $this->hasOne('Group','id','group_id')->field('id,title');
    } 
    
    public function UserInfo()
    {
        return $this->hasOne('UserInfo','uid','uid')->field('uid,nickname,headurl');
    }
    
    
    //分页数据
    public function getPageData($page=1,$count=10,$where=['status'=>['neq',-1]]){

        $data = $this::with(['Group','UserInfo'])->where($where)->page($page.','.$count)->field('id,uid,group_id,title,creattime,type,price,num,read,status,sort')->order('id desc')->select();

        return empty($data)?[]:$data->toArray();
    }
    
    //获取总条数
    public function getCount($where=['status'=>['neq',-1]]){
        return $this::with('Group')->where($where)->count();
    }

    //修改状态
    public function setStatus($id,$status){
        $data = $this->where(['id'=>$id])->update(['status'=>$status]);
        return $data;
    }
    
    //根据ID获取数据
    public function getDataById($id){
        return $this::with(['UserInfo'])->where(['id'=>$id])->find();
    }
    
    //保存数据
    public function saveData($data,$where=[]){
        $saveType = 'Curriculum.add';
        
        if(!empty($where)){$saveType = 'Curriculum.edit';}
        
        $return = $this::validate($saveType)->allowField(true)->save($data,$where);
        
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
    
    
    //创建时间
    public function setCreattimeAttr(){
        return time();
    }
    
    //设置字段值
    public function setContentsAttr($value){
        return htmlspecialchars($value,ENT_QUOTES);
    }
    
    public function getContentsAttr($value){
        return htmlspecialchars_decode($value,ENT_QUOTES);
    }
}
