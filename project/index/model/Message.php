<?php
namespace app\index\model;
use think\Db;
class Message extends Common
{
    protected $type = [
        'createtime'    =>  'timestamp:Y-m-d H:i',
    ];
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    /**
     * @保存消息
     * type  1.系统消息 2.病例秀评论 3.评论课堂 4.点赞 5.有人激活了你的邀请码
     */
    public function addData($title,$type,$uid,$url='',$contents='',$dataId=0){
        $data = $this::save(['title'=>$title,'type'=>$type,'uid'=>$uid,'status'=>1,'url'=>$url,'contents'=>$contents,'dataId'=>$dataId,'createtime'=>time()]);
        return $data;
    }
    
    /**
     * @查询今天是否签到
     */
    public function getDataByUid($uid){
        $data = $this::where(['uid'=>$uid])->order('id desc')->find();
        return $data;
    }
    
    //获取数据
    public function getDataList($uid,$page=1,$count=10){
        $where = ['uid'=>$uid,'status'=>['neq',3]];
        $data = $this::where($where)
                ->field('id,title,url,type,createtime,contents,dataId,status')
                ->page($page.','.$count)
                ->order('status asc,id desc')
                ->select();
        return empty($data)?[]:$data->toArray();
    }
    
    //获取数据
    public function findData($id){
        $where = ['id'=>$id,'status'=>['neq',3]];
        $data = $this::where($where)
                ->field('id,title,url,type,createtime,contents,dataId,status')
                ->find();
        return empty($data)?[]:$data->toArray();
    }
    
    //获取未读条数1.未读 2.已读 3.删除
    public function getUnreadCount($uid){
        return $this::where(['uid'=>$uid,'status'=>1])->count();
    }
    
    //标记为已读
    public function saveRead($id,$uid){
        return $this::save(['status'=>2],['id'=>$id,'uid'=>$uid]);
    }
}
