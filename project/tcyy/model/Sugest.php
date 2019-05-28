<?php
namespace app\tcyy\model;
use think\Db;
class Sugest extends Common
{
    
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    public function projectPrice()
    {
        return $this->hasOne('ProjectPrice','pid')->bind(['userPrice'=>'price']);
    }
    
    public function getDataById($id){
        $data = $this::where(['id'=>$id])->find();
        return $data;
    }
    
    //获取数据
    public function getDataByUid($uid,$group_id,$page=1,$count=10){
        $data = $this::with(['projectPrice'=>function($query)use ($uid){
                    $query->where(['uid'=>$uid])->field('id,pid,price');
                }])->where("uid=-1 or uid={$uid}")->where(['status'=>1,'group_id'=>$group_id])->page($page.','.$count)->field('id,number,price,image,title,uid as system')->order('sort desc,id desc')->select();

        return $data->toArray();
    }
    
    /**
     * @添加意见反馈
     */
    public function addData($data){
        $return = $this::allowField(true)->save($data);
        return $return;
    }
    
    public function setContentsAttr($value){
        return htmlspecialchars($value,ENT_QUOTES);
    }

    public function getContentsAttr($value){
        return htmlspecialchars_decode($value,ENT_QUOTES);
    }
    
}
