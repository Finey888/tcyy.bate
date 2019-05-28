<?php
namespace app\tcyy\model;
use think\Db;
class PointBook extends Common
{
    protected $type = [
        'createtime'    =>  'timestamp:Y-m-d H:i:s',
    ];
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    public function PointShop()
    {
        return $this->hasOne('PointShop','id','goodid')->field('id,title,image');
    }
    
    //获取数据
    public function getDataList($uid,$page=1,$count=10){
        $data = $this::with('PointShop')->where(['uid'=>$uid])
                ->page($page.','.$count)
                ->field('id,goodid,status,createtime,point,booknum')
                ->order('id desc')
                ->select();
        return empty($data)?[]:$data->toArray();
    }
    
    //获取数据
    public function getDataById($id){
        $data = $this::with('PointShop')->where(['id'=>$id])
                ->field('id,goodid,status,createtime,point,booknum,address,fhtime,shtime,realname,phone,province,city,area')
                ->find();
        return empty($data)?[]:$data->toArray();
    }
    
    public function getLastData(){
        $data = $this::field('id')
                ->order('id desc')
                ->find();
        return empty($data)?[]:$data->toArray();
    }
    
    //兑换商品
    public function saveData($data){
        return $this::save($data);
    }
    
}
