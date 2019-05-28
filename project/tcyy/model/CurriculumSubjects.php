<?php
namespace app\tcyy\model;
use think\Db;
class CurriculumSubjects extends Common
{
    
    protected $type = [
        'times'    =>  'timestamp:Y-m-d',
    ];
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    /**
     * @获取课程列表
     */
    public function getDataByPage($cid,$page=1,$count=10){
        $where=[
            'status'=>1,
            'cid'=>$cid
        ];
        $data = $this::where($where)->page($page.','.$count)->order('sort desc,id desc')->field('id,title,times,read,sort')->select();

        return empty($data)?[]:$data->toArray();
    }
    
    
    /**
     * @获取详情
     */
    public function getDataById($id){
        $data = $this::where(['id'=>$id])->field('id,title,times,contents,read')->find();
        return empty($data)?[]:$data->toArray();
    }
    
    /**
     * @保存课程
     */
    public function saveData($data){
        return $this::save($data);
    }
    
    public function editStatus($id){
        return $this::save(['status'=>-1],['id'=>$id]);
    }
    
   public function getImageAttr($value)
    {
        return $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$value;
    }
}
