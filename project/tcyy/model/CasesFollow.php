<?php
namespace app\tcyy\model;
use think\Db;
class CasesFollow extends Common
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
        return $this->hasOne('UserInfo','uid','touid')->field('uid,nickname,sex,headurl');
    }
    
    public function UserInfoFans()
    {
        return $this->hasOne('UserInfo','uid','uid')->field('uid,nickname,sex,headurl');
    }
    /**
     * @获取关注数据
     */
    public function getDataByTouid($uid,$touid){
        $data = $this::where(['uid'=>$uid,'touid'=>$touid])->find();
        return $data;
    }
    
    /**
     * @添加关注
     */
    public function addFollowData($uid,$touid){
        $data = $this::save(['uid'=>$uid,'touid'=>$touid,'status'=>1,'createtime'=>time()]);
        return $data;
    }
    
    /**
     * @修改关注状态
     */
    public function saveData($uid,$touid,$status){
        if($status == 1){
            $saveData = ['status'=>$status,'createtime'=>time()];
        }else{
            $saveData = ['status'=>$status,'canceltime'=>time()];
        }
        $data = $this::save($saveData,['uid'=>$uid,'touid'=>$touid]);
        return $data;
    }
    
    /**
     * @获取关注列表
     */
    public function getDataList($uid,$page,$count){
        $where=[
            'status'=>1,
            'uid'=>$uid
        ];
        $data = $this::with('UserInfo')->where($where)->page($page.','.$count)->order('id desc')->select();
        return empty($data)?[]:$data->toArray();
    }
    
    public function getDataListFans($uid,$page,$count){
        $where=[
            'status'=>1,
            'touid'=>$uid
        ];
        $data = $this::with('UserInfoFans')->where($where)->page($page.','.$count)->order('id desc')->select();
        return empty($data)?[]:$data->toArray();
    }
}
