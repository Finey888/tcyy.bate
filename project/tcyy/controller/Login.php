<?php
namespace app\tcyy\controller;
use think\Controller;
use think\Request;
class Login extends Controller
{
    private $userModel;
    
    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->userModel = new \app\tcyy\model\User();
    }
    
    /**
     * @电话登录
     * @param int $phone 电话号码
     * @param string $password 密码
     * @param int $type 登录标识
     */
    public function byPhone()
    {
        $get=input('post.');
        if(empty($get['phone'])){
            returnAjax([],'请输入手机号',2);exit();
        }
        if(empty($get['password'])){
            returnAjax([],'请输入密码',2);exit();
        }
        $data = $this->userModel->getDataByPhone($get['phone']);
        
        if(empty($data)){
            returnAjax([],'账号不存在',2);exit();
        }
        
        if($data['status'] == 2){
            returnAjax([],'账号已被禁用！',2);exit();
        }
        
        $password = md5($get['password'].config('password_str')[0]);
        
        if($password != $data['password']){
            returnAjax([],'密码错误！',2);exit();
        }
        
        //登录记录
        $code = $this->loginLog($data->toArray());
            
        $data = $this->userModel->getDataByPhone($get['phone']);
        
        $returnData = $this->getReturnData($data->toArray(), $code);
        
        returnAjax($returnData,'登录成功',1);exit();
    }
    
    /**
     * @QQWX登录
     * @param string $code 第三方唯一标识
     * @param int $type 登录标识
     * @param string $nickname 昵称
     * @param string $headurl 头像
     * @param string $sex 性别
     */
    public function byQQWX()
    {
        $get=input('post.');
        if(empty($get['code'])){
            returnAjax([],'缺少参数1！',2);exit();
        }
        
        if(empty($get['type'])){
            returnAjax([],'缺少参数2！',2);exit();
        }
        
        $data = $this->userModel->getDataByCode($get['code'],$get['type']);
        $other_code = $get['code'];
        unset($get['code']);
        
        $type = $get['type'];
        
        if(empty($data)){
            //账号不存在 自动注册
            $register = new \app\tcyy\controller\Register();
            unset($get['type']);
            
            $get['other_code'] = $other_code;
            
            $register->saveUser($get,$type);
        }
        
        //重新获取数据登录
        $data = $this->userModel->getDataByCode($other_code,$type);

        if($data['status'] == 2){
            returnAjax([],'账号已被禁用！',2);exit();
        }
        
        //登录记录
        $code = $this->loginLog($data->toArray());
        
        $data = $this->userModel->getDataByCode($other_code,$type);
        
        $returnData = $this->getReturnData($data->toArray(), $code);
  
        returnAjax($returnData,'登录成功',1);exit();
    }
    
    /**
     * @登录记录
     */
    public function loginLog($data){
        
        //判断是否存在邀请码
        if(empty($data['spread_code'])){
                $idcount = strlen($data['id']);
		//$tgcode = strtoupper(getRandomStr(3).$data['id'].getRandomStr(3));
		$qcd = 6-$idcount;
		switch($qcd){
			case 0: $qc = '';break;
			case 1: $qc = rand(0,9);break;
			case 2: $qc = rand(10,99);break;
			case 3: $qc = rand(100,999);break;
			case 4: $qc = rand(1000,9999);break;
			case 5: $qc = rand(10000,99999);break;
			case 6: $qc = rand(100000,999999);break;
		}
		$tgcode = $qc.$data['id'];
                $this->userModel->yqmSave($data['id'],$tgcode);
        }
        
        $code = md5('tcyy'.'-'.$data['id'].'-'.time());
        $saveData = [
            'code'=>$code,
            'last_login_time'=>time(),
            'last_login_ip'=>$_SERVER["REMOTE_ADDR"],
            'login_count'=>$data['login_count']+1,
            'systype'=>input('post.systype'),
            'pushid'=>input('post.pushid')
        ];
        
        $return = $this->userModel->loginSave($saveData,$data['id']);
        if(!$return){
            returnAjax([],'系统繁忙，请稍后再试！',2);exit();
        }
        return $code;
    }
    
    /**
     * @封装需要返回的数据
     */
    public function getReturnData($data,$code){
        if(!empty($data['auth_group']['headurl'])){
            if(strpos($data['auth_group']['headurl'],'http://')){
                $data['auth_group']['headurl'] = $_SERVER['HTTP_HOST'].$data['auth_group']['headurl'];
            }
        }
        return [
            'nickname'=>$data['auth_group']['nickname'],
            'header'=>$data['auth_group']['headurl'],
            'sex'=>$data['auth_group']['sex'],
            'code'=>$code,
            'phone'=>$data['phone'],
            'id'=>$data['id'],
            'spread_code'=>$data['spread_code'],//我的推广码
            'top_spread_code'=>$data['top_spread_code'],//上级推广码
            'pushid'=>$data['pushid'],//推送ID
        ];
    }
    
    /**
     * @找回密码
     * @param int $phone 电话
     * @param string $password 密码
     * @param string $rpassword 确认密码 
     * 
     */
    public function forgetPassword(){
        $get=input('post.');
        $data = $this->userModel->getDataByPhone($get['phone']);
        
        if(empty($data)){
            returnAjax([],'账号不存在',2);exit();
        }
        
        if($data['status'] == 2){
            returnAjax([],'账号已被禁用！',2);exit();
        }
        
        //验证验证码 1.注册 2.找回密码 3.解除邦定 4.邦定手机 
        $msgSMS = new \app\tcyy\controller\Sendmsg();
        $codeId = $msgSMS->verifyCode($get['phone'],$get['code'],2);
        
        unset($get['phone']);
        
        $ruelt = $this->userModel->savePassword($get,$data['id']);
        if($ruelt['status'] == 2){
            returnAjax([],$ruelt['msg'],2);exit();
        }
        
        //使用验证码
        $msgSMS->editStatus($codeId);
        
        returnAjax([],'密码重置成功',1);exit();
    }
    
    /**
     * @退出登录
     */
    public function loginOut(){
        $get=input('post.');
        if(isset($get['code'])){
            $this->userModel->editLoginType($get['code'],3);
        }
        returnAjax([], '退出成功',1);
    }
}
