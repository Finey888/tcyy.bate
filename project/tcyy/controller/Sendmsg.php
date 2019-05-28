<?php
namespace app\tcyy\controller;
use think\Controller;
use think\Request;
use think\Loader;
class Sendmsg extends Controller
{
    private $model;
    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->model = new \app\tcyy\model\Sendmsg();
    }
    
    public function index()
    {
        echo '短信发送验证模块';
    }
    //短信回调
    public function messageCallback(){
        echo '短信回调';
    }
    //发送短信
    /**
     * @发送短信验证码
     * @param int $phone 电话号码
     * @param int $type 1.注册 2.找回密码 3.解除邦定 4.邦定手机 
     */
    public function sendCode(){
        $phone=input('post.phone');
        if(empty($phone)){
            returnAjax([],'请输入有效的电话号码',2);exit();
        }
        
        $type=input('post.type');
        if(empty($type)){
            returnAjax([],'请输入未知的发送类型',2);exit();
        }
        
        //验证当前号码验证码是否使用或者使用
        $find = $this->model->getValidCodeByPhone($phone,$type);
   
        if(!empty($find)){
            returnAjax([],'验证码已发送成功,请注意查看短信！',2);exit();
        }
        
        //随机验证码
        $code = rand(pow(10,(6-1)), pow(10,6)-1);
        
        //加载第三方库
        Loader::import('messageSDK.SendTemplateSMS');
        
        $sms = new \senTemplateSMS();
        
        $ss = $sms->sendTemplateSMS($phone,[$code,'10分钟']);
        if($ss['status'] == 1){
            //短信发送成功 入库
            $data=[
              'phone'=>$phone,
              'code'=>$code,
              'type'=>$type
            ];
            
            $result = $this->model->saveData($data);

            if($result['status'] == 2){
                returnAjax([],$result['msg'],2);exit();
            }
            
            //发送验证码成功
            returnAjax(['endTime'=>date('Y-m-d H:i:s',$result['data']['endTime'])],'发送成功',1);exit();
        }else{
            $erco = (array)$ss['code'];
            returnAjax(['errorcode'=>$erco[0]],$ss['msg'],2);exit();
        }
    }
    
    /**
     * @判断当前手机是否使用验证码未过期或者未使用
     * @param int $phone 电话
     * @param int $code 验证码
     * @param int $type 验证类型
     */
    public function verifyCode($phone,$code,$type){
        $result = $this->model->getValidCodeByPhone($phone,$type);
        if(empty($result)){
            returnAjax([],'请获取验证码！',2);exit();
        }
        
        if($result->code != $code){
            returnAjax([],'验证码错误！',2);exit();
        }
        
        return $result->id;
       
    }
    
    /**
     * @修改为已验证
     */
    public function editStatus($id){
         //修改状态
        $this->model->editStatus($id);
    }
}
