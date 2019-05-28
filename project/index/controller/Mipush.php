<?php
namespace app\index\controller;
use think\Request;
use think\Loader;
class Mipush extends Common
{
    private $userModel;
    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->userModel = new \app\index\model\User();
    }
    
    /**
     * @推送消息
     */
    public function send($data,$uid){
        $userData = $this->userModel->getDataById($uid);
        $parm=[];
        if($userData['systype'] == 1){
            //安卓
            $pushId=$userData['pushid'];
            Loader::import('miPush.android_example');
            $android_sms = new \android_example();
            $parm['title'] = $data['title'];
            $parm['desc'] = $data['desc'];
            $parm['payload'] = $data['payload'];
            return $android_sms->send($parm,[$pushId]);
        }else{
            //苹果
            $pushId=$userData['pushid'];
            Loader::import('miPush.ios_example');
            $ios_sms = new \ios_example();
            $parm['desc'] = $data['desc'];
            $parm['payload'] = $data['payload'];
            return $ios_sms->send($parm,[$pushId]);
        }
    }
}
