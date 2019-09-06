<?php
namespace app\index\model;

class CoursesUser extends Common {

    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }

    //提现操作
    public function ordersWithdrawing($id){
        return $this -> where(['id'=>$id]) -> update(['withdraw' => 1]);
    }
}