<?php
namespace app\tcyy\model;
use think\Db;
class Curriculum extends Common
{
    
    protected $type = [
        'creattime'    =>  'timestamp:Y-m-d',
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
    
    /**
     * @获取课程列表
     */
    public function getDataByPage($group_id,$page=1,$count=10){
        $where=[
            'status'=>1,
            'group_id'=>$group_id
        ];
        $data = $this::with('UserInfo')->where($where)->page($page.','.$count)->order('sort desc,id desc')->select();

        return empty($data)?[]:$data->toArray();
    }
    
    
    /**
     * @获取详情
     */
    public function getDataById($id){
        $data = $this::with('UserInfo')->where(['id'=>$id])->find();
        return empty($data)?[]:$data->toArray();
    }
    
   public function getImageAttr($value)
    {
        return $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$value;
    }
}
