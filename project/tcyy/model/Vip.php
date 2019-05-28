<?php
namespace app\tcyy\model;
use think\Db;
class Vip extends Common
{
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    /**
     * @获取具体会员价格
     */
    public function getDataFind($id){
        $where=[
            'status'=>1,
            'id'=>$id
        ];
        return $this::where($where)->field('id,mouth,name,price')->find();
    }
    
    /**
     * @获取获取列表
     */
    public function getDataList(){
        $where=[
            'status'=>1,
        ];
        $data = $this::where($where)->field('id,mouth,name,price')->select();
        return empty($data)?[]:$data->toArray();
    }
}
