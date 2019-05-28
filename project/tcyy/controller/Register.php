<?php
namespace app\tcyy\controller;
use think\Controller;
use think\Request;
class Register extends Controller
{
    private $userModel;
    private $userInfoModel;
    
    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->userModel = new \app\tcyy\model\User();
        $this->userInfoModel = new \app\tcyy\model\UserInfo();
    }
    
    /**
     * @电话注册
     * @param string $phone 电话
     * @param string $code 短信验证码
     * @param int $type 账号标识
     * @param string $password 密码
     * @param string $rpassword 确认密码
     */
    public function byPhone()
    {
        $get=input('post.');
        
        //验证验证码
        $msgSMS = new \app\tcyy\controller\Sendmsg();
        $codeId = $msgSMS->verifyCode($get['phone'],$get['code'],1);
        
        $type = $get['type'];
        unset($get['type']);
        
        $this->saveUser($get, $type);
        
        //使用验证码
        $msgSMS->editStatus($codeId);
        
        returnAjax([],'注册成功',1);exit();
    }
    
    /**
     * @保存用户信息
     */
    public function saveUser($get,$type){
        $userResult = $this->userModel->addData($get,$type); //添加用户
        if($userResult['status'] == 2){
            returnAjax([],$userResult['msg'],2);exit();//新增错误
        }
        
        //保存用户其他信息
        
        //组装用户信息数据
        $get['uid'] = $userResult['id'];//用户ID
        $get['headurl'] = empty($get['headurl'])?'':$get['headurl'];//默认头像
        $get['nickname2'] = $get['nickname'];
        $get['nickname'] = json_encode($get['nickname']);
        $userInfoResult = $this->userInfoModel->addData($get);//添加用户信息
    
        if($userInfoResult['status'] == 2){
            //用户信息添加失败，删除用户表
            $this->userModel->del($userResult['id']);
            returnAjax([],$userInfoResult['msg'],2);exit();//新增错误
        }
        
        return ['status'=>1,'id'=>$userResult['id']];
    }
}
