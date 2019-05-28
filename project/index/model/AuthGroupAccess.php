<?php
namespace app\index\model;

class AuthGroupAccess extends Common
{
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    //保存数据
    public function saveData($data,$where=[]){

        $saveType = 'Auth_group_access.add';
        if(!empty($where)){$saveType = 'Auth_group_access.edit';}
        
        $return = $this::validate($saveType)->allowField(true)->save($data,$where);
        
        return $this::getError();
    }
}
