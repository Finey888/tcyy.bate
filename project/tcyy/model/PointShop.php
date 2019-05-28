<?php
namespace app\tcyy\model;
use think\Db;
class PointShop extends Common
{
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    //获取数据
    public function getDataList($page=1,$count=10){
        $data = $this::where(['status'=>1,'endtime'=>['>=', strtotime(date('Y-m-d 00:00:00',time()))]])
                ->page($page.','.$count)
                ->field('id,title,point,price,image,contents,number,consume')
                ->order('sort desc,id desc')
                ->select();
        return empty($data)?[]:$data->toArray();
    }
    
    //获取数据
    public function getDataById($id){
        $data = $this::where(['id'=>$id])
                ->field('id,title,point,price,image,contents,number,consume')
                ->find();
        return empty($data)?[]:$data->toArray();
    }
    
    //更新库存
    public function saveConsume($id){
            $this::where(['id'=>$id])->setInc('consume');
    }
    
    public function getImageAttr($value)
    {
        return $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$value;
    }
}
