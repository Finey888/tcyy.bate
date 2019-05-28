<?php
namespace app\tcyy\model;
use think\Db;
class Project extends Common
{
    //新增时自动写入配置字段
    protected $insert = ['createtime','sort']; 
    
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    public function projectPrice()
    {
        return $this->hasOne('ProjectPrice','pid')->bind(['userPrice'=>'price']);
    }
    
    public function getDataById($id){
        $data = $this::where(['id'=>$id])->find();
        return $data;
    }
    
    //获取数据
    public function getDataByUid($uid,$group_id,$page=1,$count=10){

        /**
         * $return = $this::with(['Manual'=>function($query)use ($manualWhere){
                    $query->where($manualWhere)->field('id,title,group')->order('sort desc,id desc');
                }])->where($where)->select();
         */
        $data = $this::with(['projectPrice'=>function($query)use ($uid){
                    $query->where(['uid'=>$uid])->field('id,pid,price');
                }])->where("uid=-1 or uid={$uid}")->where(['status'=>1,'group_id'=>$group_id])->page($page.','.$count)->field('id,number,price,image,title,uid as system')->order('sort desc,id desc')->select();

        return $data->toArray();
    }
    
    /**
     * @添加用户项目
     * @param string $number 编号
     * @param int $price 价钱
     * @param string $title 标题
     * @param int $group_id 分类ID
     * @param int $uid 用户ID
     * @param string $image 图片
     */
    public function addData($data,$where=[]){
        if(empty($where)){
            $saveType = 'Project.add';
        }else{
            $saveType = 'Project.edit';
        }
        
        $return = $this::validate($saveType)->allowField(true)->save($data,$where);
      
        return empty($this::getError())?['status'=>1,'data'=>$this->toArray()]:['status'=>2,'msg'=>$this::getError()];
    }
    
    /**
     * @删除项目
     */
    public function del($id,$uid){
        return $this::save(['status'=>-1],['id'=>$id,'uid'=>$uid]);
    }
    /**
     * 修改器
     * 修改器的作用是可以在数据赋值的时候自动进行转换处理
     * 方法名规则：setNameAttr() ，Name：字段名
     */
    //排序
    public function setSortAttr(){
        return -1;
    }
    
    //注册时间
    public function setCreatetimeAttr(){
        return time();
    }
    
    //获取图片数据
    public function getImageAttr($value){
        if(stripos($value,"http://") == false){
            return $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$value;
        }else{
            return str_replace($_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'], '', $value);
        }
    }
    
    //获取图片数据
    public function setImageAttr($value){
        if(stripos($value,"http://") == false){
            return str_replace($_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'], '', $value);
        }else{
            return $value;
        }
    }
}
