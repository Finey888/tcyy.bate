<?php
namespace app\tcyy\model;
use think\Db;
class User extends Common
{
    //新增时自动写入配置字段
    protected $insert = ['reg_ip','regtime']; 
    
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    public function AuthGroup()
    {
        return $this->hasOne('UserInfo','uid');
    }
    
    
    //根据ID获取数据
    public function getDataById($id){
        return $this::with('AuthGroup')->where(['id'=>$id])->find();
    }
    
    //根据电话获取数据
    public function getDataByPhone($phone){
        return $this::with('AuthGroup')->where(['phone'=>$phone])->find();
    }
    
    //根据第三方标识码获取数据
    public function getDataByCode($code,$type){
        return $this::with('AuthGroup')->where(['other_code'=>$code,'login_type'=>$type])->find();
    }
    
    //根据激活码获取用户
    public function getDataBySpread($spread_code){
        return $this::where(['spread_code'=>$spread_code])->find();
    }
     /**
     * @根据传递的字段值保存数据
     */
    public function saveDataByFiled($id,$field){
        $return = $this::save($field,['id'=>$id]);
        return $return;
    }
    
    /**
     * @登录数据
     * @param string $code 登录唯一码
     * @param int $last_login_time 登录时间
     * @param int $last_login_ip 登录IP
     */
    public function loginSave($data,$id){
        return $this::allowField(true)->save($data,['id'=>$id]);
    }
    
    //保存邀请码
    public function yqmSave($id,$code){
        return $this::allowField(true)->save(['spread_code'=>$code],['id'=>$id]);
    }
    
    //注册数据
    /**
     * @注册会员数据
     * @param array $data 注册信息
     * @param int $type 1.手机  2.QQ  3.微信
     */
    public function addData($data,$type=1){
        $saveType = 'User.addByPhone';
        $data['login_type'] = $type;
        if($type == 1){
            $saveType = 'User.addByPhone';
        }elseif($type == 2){
            $saveType = 'User.addByQQ';
        }elseif($type == 3){
            $saveType = 'User.addByWx';
        }
      
        $return = $this::validate($saveType)->allowField(true)->save($data);
        
        return empty($this::getError())?['status'=>1,'id'=>$this->id]:['status'=>2,'msg'=>$this::getError()];
    }
    
    /**
     * @注册会员数据
     * @param array $data 需要保存的数据
     * @param int $id 电话
     */
    public function savePassword($data,$id){
        $saveType = 'User.setPasswordByPhone';
        
        $return = $this::validate($saveType)->allowField(true)->save($data,['id'=>$id]);
        
        return empty($this::getError())?['status'=>1,'id'=>$id]:['status'=>2,'msg'=>$this::getError()];
    }
    
    /**
     * @修改登录状态
     * @param int $id 用户ID
     * @type int 1.登陆  2.未登录  3.退出登录
     */
    public function editLoginType($code,$type){
        $data = $this::get(['code'=>$code]);
        if(!isset($data->id)){
            return '';
        }
        $return = $this::save(['is_login'=>$type,'code'=>md5('tcyy'.'-'.$data->id.'-'.time())],['code'=>$code]);
        return $return;
    }
    
    /**
     * @激活激活码。
     */
    public function saveSpreadCode($uid,$code){
        $return = $this::save(['top_spread_code'=>$code,'total_point'=>['exp','total_point+10'],'point'=>['exp','point+10']],['id'=>$uid]);
        return $return;
    }
    
    /**
     * @查询激活码
     */
    public function findSpreadCode($code){
        return $this->where(['spread_code'=>$code])->find();
    }
    
    public function savePoint($uid,$point,$jj=1){
        if($jj==1){
            $str = 'point+'.$point;
        }else{
            $str = 'point-'.$point;
        }
        $return = $this::save(['point'=>['exp',$str]],['id'=>$uid]);
        return $return;
    }
    
    /**
     * @检查是否登录
     */
    public function checkLogin($code){
        $data = $this::with('AuthGroup')->where(['code'=>$code])->find();
        return (!isset($data->id))?false:$data;
    }
    
    /**
     * @获取总积分
     */
    public function getTotalPoint($uid){
        return $this->where(['id'=>$uid])->field('id,point')->find();
    }
    
    /**
     * @删除数据
     * @param int $id 用户ID
     */
    public function del($id){
        $this::where('id','=',$id)->delete();
    }
    
    /**
     * @充值会员
     */
    public function saveVip($uid,$endtime){
        $this::save(['vip_end_date'=>$endtime],['id'=>$uid]);
    }

    /**
     * 修改器
     * 修改器的作用是可以在数据赋值的时候自动进行转换处理
     * 方法名规则：setNameAttr() ，Name：字段名
     */
    
    //密码修改器
    public function setPasswordAttr($value){
        return md5($value.config('password_str')[0]);
    }
    
    //注册时间
    public function setRegtimeAttr(){
        return time();
    }
    
    //注册登录IP
    public function setRegIpAttr(){
        return $_SERVER["REMOTE_ADDR"];
    }
}
