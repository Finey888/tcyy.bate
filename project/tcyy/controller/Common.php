<?php
namespace app\tcyy\controller;
use think\Controller;
use think\Request;

class Common extends Controller
{
    protected $userData;
    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $post = input('post.');
  
        if(!isset($post['code'])){
            returnAjax([], '请登录！', 4);
        }
        $this->userData = $this->checkLogin($post['code']);
    }
    
    /**
     * @p判断是否登录
     */
    public function checkLogin($code){
        if(empty($code)){returnAjax([], '请登录！', 4);}
        $userModel = new \app\tcyy\model\User();
        $data = $userModel->checkLogin($code);
        if(!$data){
            returnAjax([], '请登录！', 4);
        }
        return $data;
    }
    
    public function getUserData(){
        return $this->userData;
    }
}
