<?php
namespace app\tcyy\model;
use think\Db;
class Knowledge extends Common
{
    protected $type = [
        'times'    =>  'timestamp:Y-m-d',
    ];
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    /**
     * @获取知识库列表
     */
    public function getDataByPage($group_id,$page=1,$count=10){
        $where=[
            'status'=>1,
            'gid'=>$group_id
        ];
        return $this::where($where)->field('id,image,info,title,times,read')->page($page.','.$count)->order('sort desc,id desc')->select();
    }
    
    /**
     * @获取详情数据
     */
    public function getDataById($id){
        $where=[
            'status'=>1,
            'id'=>$id
        ];
        return $this::where($where)->field('id,title,times,author,contents,read')->order('sort desc,id desc')->find();
    }
    
    /**
     * @添加阅读量
     */
    public function saveRead($id){
        $this::where(['id'=>$id])->setInc('read');
    }


    public function getImageAttr($value)
    {
        return $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$value;
    }
}
