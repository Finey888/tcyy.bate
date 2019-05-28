<?php
namespace app\index\model;
use think\Db;
class UserInfo extends Common
{
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    public function getNicknameAttr($value){
       
        $nickname = json_decode($value);
      
        if(empty($nickname)){
            return $value;
        }else{
            return $nickname;
        }
    }
}
