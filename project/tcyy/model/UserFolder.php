<?php
namespace app\tcyy\model;
use think\Db;
class UserFolder extends Common
{
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    /**
     * @新增文件夹
     */
    public function addData($parm,$uid){
        $data = $this::allowField(true)->save(['status'=>1,'uid'=>$uid,'title'=>$parm['title'],'createtime'=>time(),'group_id'=>$parm['groupid']]);
        
        return empty($data)?[]:$this->data['id'];
    }
    
    public function editName($parm,$uid){
        $data = $this::allowField(true)->save(['title'=>$parm['title']],['id'=>$parm['id'],'uid'=>$uid]);
        return $data;
    }
    
    //获取数据
    public function getDataList($uid,$group_id){
         $where = ['uid'=>$uid,'status'=>1,'group_id'=>$group_id];

        $data = $this::where($where)
                ->field('id,title')
                ->order('id desc')
                ->select();
        return empty($data)?[]:$data->toArray();
    }
    
    //删除
    public function del($id,$uid){
        $data = $this::allowField(true)->save(['status'=>-1],['uid'=>$uid,'id'=>$id]);
        return $data;
    }
}
