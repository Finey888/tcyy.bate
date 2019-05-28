<?php
namespace app\tcyy\model;
use think\Db;
class Pay extends Common
{
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    /**
     * @获取订单信息
     */
    public function getDataFind(){
        return $this::order('id desc')->find();
    }
    
    /**
     * @获取shuju 
     */
    public function getDataByTrade($out_trade_no){
        return $this::where(['out_trade_no'=>$out_trade_no])->find();
    }
    
    /**
     * @生成订单
     */
    public function saveData($data){
        return $this::allowField(true)->save($data);
    }
    
    /**
     * @生成订单
     */
    public function updateData($data,$where){
        return $this::allowField(true)->save($data,$where);
    }
}
