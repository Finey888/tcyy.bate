<?php
namespace app\index\model;
use think\Db;
class CurriculumSubjects extends Common
{
    protected $type = [
        'times'  =>  'timestamp:Y-m-d',
    ];
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    //分页数据
    public function getPageData($page=1,$count=10,$where=['status'=>['neq',-1]]){

        $data = $this::where($where)->page($page.','.$count)->field('id,title,times,read,status,sort,cid')->order('id desc')->select();

        return empty($data)?[]:$data->toArray();
    }
    
    //获取总条数
    public function getCount($where=['status'=>['neq',-1]]){
        return $this::where($where)->count();
    }

    //修改状态
    public function setStatus($id,$status){
        $data = $this->where(['id'=>$id])->update(['status'=>$status]);
        return $data;
    }
    
    //根据ID获取数据
    public function getDataById($id){
        return $this::where(['id'=>$id])->find();
    }
    
    /**
     * 修改器
     * 修改器的作用是可以在数据赋值的时候自动进行转换处理
     * 方法名规则：setNameAttr() ，Name：字段名
     */
    
    public function getContentsAttr($value){
        return htmlspecialchars_decode($value,ENT_QUOTES);
    }
}
