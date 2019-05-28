<?php
namespace app\tcyy\controller;
use think\Request;
class User extends Common
{
    private $userModel;
    private $userInfoModel;
    private $follwModel;
    private $pointUserModel;
    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->userModel = new \app\tcyy\model\User();
        $this->userInfoModel = new \app\tcyy\model\UserInfo();
        $this->follwModel = new \app\tcyy\model\CasesFollow();
        $this->pointUserModel = new \app\tcyy\model\PointUser();
        
    }
    
    //获取用户基础信息
    public function getInfo(){
        $data = $this->userModel->getDataById($this->userData->id);
        $data = $data->toArray();
        $return=[
            'id'=>$data['id'],
            'nickname'=>$data['auth_group']['nickname'],
            'sex'=>$data['auth_group']['sex'],
            'headurl'=>$data['auth_group']['headurl'],
            'phone'=>$data['auth_group']['phone'],
            'address'=>$data['auth_group']['address'],//详细地址
            'province'=>$data['auth_group']['province'],//省份
            'city'=>$data['auth_group']['city'],//城市
            'area'=>$data['auth_group']['area'],//区
            'weixin'=>$data['auth_group']['weixin'],
            'qq'=>$data['auth_group']['qq'],
            'email'=>$data['auth_group']['email'],
        ];
        returnAjax($return,'数据获取成功！',1);exit();
    }
    
    /**
     * @根据字段保存数据
     */
    public function saveDataByFiled(){
        $get=input('post.');
        if(empty($get['field'])){
            returnAjax([],'请设置需要修改的字段！',2);exit();
        }
        $field=['nickname','sex','headurl','address','province','city','area','weixin','qq','email'];
        $saveData=[];
        foreach($get['field'] as $k=>$v){
            if(in_array($k, $field)){
                if($k == 'nickname'){
                    $saveData[$k]= json_encode($v);
                    $saveData['nickname2']= $v;
                }else{
                    $saveData[$k]=$v;
                }
                
            }else{
                returnAjax([],'未知参数！！',2);exit();
            }
        }
        
        $return = $this->userInfoModel->saveDataByFiled($this->userData->id,$saveData);
        if(!$return){
            returnAjax([],'修改失败！',2);exit();
        }
        returnAjax([],'保存成功！',1);exit();
    }
    
    /**
     * @移除点的话绑定
     */
    public function removePhone(){
        $get=input('post.');
        //验证验证码 1.注册 2.找回密码 3.解除邦定 4.邦定手机 
        $msgSMS = new \app\tcyy\controller\Sendmsg();
        $codeId = $msgSMS->verifyCode($this->userData->phone,$get['msgcode'],3);
        
        $find = $this->userModel->getDataById($this->userData->id);
        
        if(empty($find['other_code'])){
            returnAjax([],'电话注册无法解除绑定！',2);exit();
        }
        
        /*$return1 = $this->userModel->saveDataByFiled($this->userData->id,['phone'=>'']);
        if(!$return1){
            returnAjax([],'解除绑定失败！',2);exit();
        }*/
        
        $return = $this->userInfoModel->saveDataByFiled($this->userData->id,['phone'=>'']);
        
        if(!$return){
            returnAjax([],'解除绑定失败！',2);exit();
        }
        
        //使用验证码
        $msgSMS->editStatus($codeId);
        
        returnAjax([],'解除绑定成功！',1);exit();
    }
    
    /**
     * @绑定手机
     */
    public function bindPhone(){
        $get=input('post.');
        
        if(empty($get['phone'])){
            returnAjax([],'请输入手机号码！',2);exit();
        }
        
        if(empty($get['msgcode'])){
            returnAjax([],'请输入验证码！',2);exit();
        }
        
        if(empty($get['password'])){
            returnAjax([],'请输入密码！',2);exit();
        }
        
       $find = $this->userModel->getDataByPhone($get['phone']);
       
       if(!empty($find)){
           returnAjax([],'电话已存在！',2);exit();
       }
       
        //验证验证码 1.注册 2.找回密码 3.解除邦定 4.邦定手机 
        $msgSMS = new \app\tcyy\controller\Sendmsg();
        $codeId = $msgSMS->verifyCode($get['phone'],$get['msgcode'],4);
        
        
       /* $return1 = $this->userModel->saveDataByFiled($this->userData->id,['phone'=>$get['phone'],'password'=>$get['password']]);
        if(!$return1){
            returnAjax([],'绑定电话失败！',2);exit();
        }*/
        
        $return = $this->userInfoModel->saveDataByFiled($this->userData->id,['phone'=>$get['phone'],'password'=>$get['password']]);
        
        if(!$return){
            returnAjax([],'绑定电话失败！',2);exit();
        }
        
        //使用验证码
        $msgSMS->editStatus($codeId);
        
        returnAjax([],'绑定电话成功！',1);exit();
    }
    
    /**
     * @我的关注
     */
    public function myFollow(){
        $get=input('post.');
        
        $page = empty($get['page'])?1:$get['page'];
        $limit = empty($get['limit'])?10:$get['limit'];

        $data = $this->follwModel->getDataList($this->userData->id,$page,$limit);
        
        $return=[];
        foreach($data as $k=>$v){
            $return[$k]['id']=$v['id'];
            $return[$k]['nickname']=$v['user_info']['nickname'];
            $return[$k]['sex']=$v['user_info']['sex'];
            $return[$k]['headurl']=$v['user_info']['headurl'];
            $return[$k]['uid']=$v['uid'];
        }
        
        returnAjax(['data'=>$return,'page'=>$page],'获取数据成功',1);exit();
    }
    
    /**
     * @我的粉丝
     */
    public function myFans(){
        $get=input('post.');
        
        $page = empty($get['page'])?1:$get['page'];
        $limit = empty($get['limit'])?10:$get['limit'];

        $data = $this->follwModel->getDataListFans($this->userData->id,$page,$limit);
        
        $return=[];
        foreach($data as $k=>$v){
            $return[$k]['id']=$v['id'];
            $return[$k]['nickname']=$v['user_info_fans']['nickname'];
            $return[$k]['sex']=$v['user_info_fans']['sex'];
            $return[$k]['headurl']=$v['user_info_fans']['headurl'];
            $return[$k]['uid']=$v['uid'];
        }
        
        returnAjax(['data'=>$return,'page'=>$page],'获取数据成功',1);exit();
    }
    
    /**
     * @激活邀请码
     */
    public function activationCode(){
        $get=input('post.');
       
        if(empty($get['spread_code'])){
            returnAjax([],'请输入邀请码！',2);exit();
        }
        
        $data1 = $this->userModel->getDataById($this->userData->id);
        
        if($get['spread_code'] == $data1['spread_code']){
            returnAjax([],'不能使用自己的邀请码！',2);exit();
        }
        
        if(!empty($data1['top_spread_code'])){
            returnAjax([],'您已经使用过邀请码！',2);exit();
        }
        
        $data3 = $this->userModel->findSpreadCode($get['spread_code']);
        if(empty($data3)){
            returnAjax([],'请输入正确的邀请码！',2);exit();
        }
        
        $this->userModel->saveSpreadCode($this->userData->id, $get['spread_code']);
        
        $data = $this->userModel->getDataById($this->userData->id);
        
        if(!$data){
            returnAjax([],'激活失败！',2);exit();
        }
        $jhjf = 50;
        $this->pointUserModel->addData(5, 1, $this->userData->id,$jhjf, $data1['point']); //添加积分记录
       
        $loginCont = new \app\tcyy\controller\Login();
        $returnData = $loginCont->getReturnData($data, $data['code']);
        
        //给上级激活码加积分
        $topData = $this->userModel->getDataBySpread($get['spread_code']);
        
        $this->userModel->savePoint($topData['id'],10,1);
        
        $userpoint = new \app\tcyy\model\PointUser();
        $userpoint->addData(6, 1, $topData['id'], 10, $topData['point']); //添加积分记录
        
        $mesModel = new \app\tcyy\model\Message();
        $mesModel->addData('系统消息', 1, $topData['id'],'','有人激活了你的邀请码！积分+10！','');
        
        returnAjax($returnData,'积分+'.$jhjf.'！',1);exit();
    }
    
    /**
     * @签到+3分
     */
    public function sign(){
        //查询今天是否已经签到
        $pointData = $this->pointUserModel->getDataByUid($this->userData->id);

        if(!empty($pointData)){
            $lastData = date('Y-m-d',strtotime($pointData['times']));
            $date = date('Y-m-d',time());
            if($lastData == $date){
                returnAjax([],'已经签到！',2);exit();
            }
        }
        
        $userData = $this->userModel->getDataById($this->userData->id);
        
        $return = $this->pointUserModel->addData(1, 1, $this->userData->id, 3, $userData['point']);
        
        if(!$return){
            returnAjax([],'签到失败！请稍后再试！',2);exit();
        }
        
        $this->userModel->savePoint($this->userData->id, 3);
        
        returnAjax([],'签到成功！+3',1);exit();
    }
    
    /**
     * @积分列表
     */
    public function pointList(){
        $get = input('post.');
        $page = empty($get['page'])?1:$get['page'];
        $limit = empty($get['limit'])?10:$get['limit'];
        $data = $this->pointUserModel->getDataList($this->userData->id,'',$page,$limit);
        foreach($data as $k=>$v){
            $data[$k]['typeStr']=getPointType($v['type']);
            if(empty($v['point_shop'])){
                $data[$k]['point_shop']=[];
            }
        }
        returnAjax(['data'=>$data,'page'=>$page],'获取成功',1);exit();          
    }
    
    /**
     * @获取会员价目列表
     */
    public function vipList(){
        $vipModel = new \app\tcyy\model\Vip();
        $data = $vipModel->getDataList();
        returnAjax($data,'获取成功',1);exit();   
    }
    
    
    /**
     * @获取会员信息
     */
    public function getVip(){
        $data = $this->userModel->getDataById($this->userData->id);
        $data = $data->toArray();
        $return['isend'] = 1;
        if($data['vip_end_date']<strtotime(date('Y-m-d 00:00:00',time()))){
            $return['isend'] = 2;
        }
        
        if($data['vip_end_date'] != '0'){
            $return['date'] = date('Y-m-d H:i:s',$data['vip_end_date']);
        }else{
            $return['date'] = 0;
        }
        
        returnAjax($return,'获取成功',1);exit();   
    }
}
