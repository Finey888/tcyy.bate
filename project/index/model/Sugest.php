<?php
namespace app\index\model;
use think\Db;
class Sugest extends Common
{
    
    protected $type = [
        'time1'  =>  'timestamp:Y-m-d H:i:s',
        'time2'  =>  'timestamp:Y-m-d H:i:s',
    ];
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    public function userInfo()
    {
        return $this->hasOne('UserInfo','uid','uid');
    }
    
    public function getDataById($id){
        $data = $this::with('userInfo')->where(['id'=>$id])->find();
        return $data;
    }
    
    //获取数据
    public function getPageData($page=1,$count=10,$where,$sort){
        $data = $this::with('userInfo')->where($where)->page($page.','.$count)->order($sort)->select();

        return empty($data)?[]:$data->toArray();
    }
    
    //总条数
    public function getCount($where){
         $data = $this::where($where)->count();
         return $data;
    }
    
    /**
     * @添加意见反馈
     */
    public function addData($data,$where){
        $return = $this::allowField(true)->save($data,$where);
     
        return $return;
    }
    
    public function setContentsAttr($value){
        return htmlspecialchars($value,ENT_QUOTES);
    }

    public function getContentsAttr($value){
        return htmlspecialchars_decode($value,ENT_QUOTES);
    }
}
