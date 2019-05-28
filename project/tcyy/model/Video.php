<?php
namespace app\tcyy\model;
use think\Db;
class Video extends Common
{
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    /**
     * @获取视频列表
     */
    public function getDataByPage($group_id,$page=1,$count=10){
        $where=[
            'status'=>1,
            'group_id'=>$group_id
        ];
        return $this::where($where)->field('id,url,image,title')->page($page.','.$count)->order('sort desc,id desc')->select();
    }
    
    
    public function getImageAttr($value)
    {
        return $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$value;
    }
    
    public function getUrlAttr($value)
    {
        return $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$value;
    }
}
