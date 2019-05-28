<?php
namespace app\tcyy\model;
use think\Db;
class UserInfo extends Common
{
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    public function User()
    {
        return $this->hasOne('user','id','uid');
    }
    
    //获取
    public function getDataById($id){
        return $this::where(['id'=>$id])->find();
    }
    
    public function getDataByUid($id){
        return $this::where(['uid'=>$id])->find();
    }

    //注册数据
    /**
     * @注册会员数据
     * @param array $data 注册信息
     * @param int $type 1.手机  2.QQ  3.微信
     */
    public function addData($data,$type=1){
        $saveType = 'UserInfo.addByPhone';
        if($type == 1){
            $saveType = 'UserInfo.addByPhone';
        }elseif($type == 2){
            $saveType = 'UserInfo.addByQQ';
        }elseif($type == 3){
            $saveType = 'UserInfo.addByWx';
        }
        $return = $this::validate($saveType)->allowField(true)->save($data);
        return empty($this::getError())?['status'=>1,'id'=>$this->uid]:['status'=>2,'msg'=>$this::getError()];
    }
    
    /**
     * @根据传递的字段值保存数据
     */
    public function saveDataByFiled($id,$field){
        $return = $this::save($field,['uid'=>$id]);
        return $return;
    }
    
    public function getNicknameAttr($value)
    {
        $nickname = json_decode($value,true);
        if(empty($nickname)){
            return $value;
        }else{
            return $nickname;
        }
    }
    
        
        
}
