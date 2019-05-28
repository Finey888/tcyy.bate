<?php
namespace app\tcyy\model;
use think\Db;
class AdvertTime extends Common
{
    protected $type = [
        'times'    =>  'timestamp:Y-m-d H:i:s',
    ];
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    /**
     * @查询启动页面图片
     */
    public function selectData(){
        $data = $this::order('id desc')->find();
        return $data;
    }
    
}
