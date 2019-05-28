<?php
namespace app\s\model;
use think\Db;
class Knowledge extends Common
{
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    /**
     * @获取详情数据
     */
    public function getDataById($id){
        $where=[
            'status'=>1,
            'id'=>$id
        ];
        return $this::where($where)->field('id,title,times,image,contents,read')->order('sort desc,id desc')->find();
    }
}
