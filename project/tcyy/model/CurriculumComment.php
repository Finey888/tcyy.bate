<?php
namespace app\tcyy\model;
use think\Db;
class CurriculumComment extends Common
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
    
    public function UserInfo()
    {
        return $this->hasOne('UserInfo','uid','uid')->field('id,uid,nickname,headurl');
    }
    
    /**
     * @获取课程列表
     */
    public function getDataByPage($cid,$pid=0,$page=1,$count=10){
        $where=[
            'status'=>1,
            'cid'=>$cid,
            'pid'=>$pid
        ];
        $data = $this::with('UserInfo')->where($where)->page($page.','.$count)->order('id desc')->select();

        return empty($data)?[]:$data->toArray();
    }
    
    public function getChildCommentCount($cid,$pid=0){
        return $this::where(['cid'=>$cid,'pid'=>$pid])->count();
    }
    
    /**
     * @添加评论
     */
    public function addData($uid,$cid,$pid,$contents){
        $data = $this::save(['uid'=>$uid,'cid'=>$cid,'pid'=>$pid,'contents'=>$contents,'times'=>time()]);
        return $data;
    }
    
}
