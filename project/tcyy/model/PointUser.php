<?php
namespace app\tcyy\model;
use think\Db;
class PointUser extends Common
{
    protected $type = [
        'times'    =>  'timestamp:Y-m-d H:i',
    ];
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    public function PointShop()
    {
        return $this->hasOne('PointShop','id','good_id')->field('id,title,image');
    }
    
    /**
     * @获取详情
     * type  1.签到  2.发布并列秀 3.点赞 4.积分商城 5.激活邀请码 6.有人激活邀请码
     */
    public function addData($type,$jj,$uid,$point,$old_point=0,$good_id=0){
        $data = $this::save(['type'=>$type,'uid'=>$uid,'status'=>$jj,'point'=>$point,'old_point'=>$old_point,'good_id'=>$good_id,'times'=>time()]);
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
    public function getDataList($uid,$type='',$page=1,$count=10){
        if(empty($status)){
            $where = ['uid'=>$uid];
        }else{
             $where = ['uid'=>$uid,'type'=>$type];
        }
        $data = $this::with('PointShop')->where($where)
                ->page($page.','.$count)
                ->order('id desc')
                ->select();
        return empty($data)?[]:$data->toArray();
    }
    
    
}
