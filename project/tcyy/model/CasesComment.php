<?php
namespace app\tcyy\model;
use think\Db;
class CasesComment extends Common
{
    protected $type = [
        'times'    =>  'timestamp:Y-m-d H:i:s',
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
    
    public function ToUserInfo()
    {
        return $this->hasOne('UserInfo','uid','touid');
    }
    
    /**
     * @添加评论
     */
    public function addData($uid,$case_id,$pid,$contents,$touid=0){
        $data = $this::save(['uid'=>$uid,'case_id'=>$case_id,'pid'=>$pid,'contents'=>$contents,'times'=>time(),'touid'=>$touid]);
        return $data?$this->id:false;
    }
    
    /**
     * @获取评论信息
     */
    public function getListData($case_id,$pid=0,$page=1,$count=10){
        $data = $this::with(['UserInfo'=>function($query){
                    $query->field('id,uid,nickname,headurl');
                },'ToUserInfo'=>function($query){
                    $query->field('id,uid,nickname,headurl');
                }])
                ->where(['case_id'=>$case_id,'pid'=>$pid,'status'=>1])
                ->page($page.','.$count)
                ->order('id desc')
                ->select();
        return $data->toArray();
    }
    
    /**
     * @获取子级评论条数
     */
    public function getChildCommentCount($case_id,$pid){
        $data = $this::where(['case_id'=>$case_id,'pid'=>$pid,'status'=>1])->count();
        return $data;
    }
    
    //删除评论
    public function delComment($id){
        $data = $this::save(['status'=>-1],['id'=>$id]);
        return $data;
    }
    
    //删除子级评论
    public function delChildComment($id){
        $data = $this::save(['status'=>-1],['pid'=>$id]);
        return $data;
    }
    
    //查询条评论
    public function getDataById($id){
        $data = $this::where(['id'=>$id])->find();
        return $data;
    }
    
}
