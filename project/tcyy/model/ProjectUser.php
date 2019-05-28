<?php
namespace app\tcyy\model;
use think\Db;
class ProjectUser extends Common
{
    //新增时自动写入配置字段
    protected $insert = ['createtime']; 
    protected $type = [
        'booktime'    =>  'timestamp:Y-m-d H:i',
    ];
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    public function projectTiming()
    {
        return $this->hasMany('ProjectTiming','puid');
    }
    
    public function group()
    {
        return $this->hasOne('Group','id','group_id');
    }
    
    public function projectTimingOne()
    {
        return $this->hasOne('ProjectTiming','puid');
    }
    
    /**
     * @获取详情
     */
    public function getDataDetails($id){
        $data = $this::with('projectTimingOne')->where(['id'=>$id])->find();
        if(!empty($data)){
            $data=$data->toArray();
        }
        return $data;
    }
    
    //获取数据
    public function getDataList($uid,$status,$page=1,$count=10,$times=''){
        if(empty($status)){
            $where = ['status'=>1,'uid'=>$uid];
        }else{
             $where = ['status'=>1,'uid'=>$uid,'type'=>$status];
        }
        
        if(!empty($times)){
          
            $BeginDate=date('Y-m-01 00:00:00', strtotime($times));
            $endtime = date('Y-m-d 23:59:59', strtotime("$BeginDate +1 month -1 day"));

            $where[] = ['exp','booktime>='.strtotime($BeginDate).' and booktime<='.strtotime($endtime)];
        }
        
        $data = $this::where($where)
                ->page($page.','.$count)
                ->field('id,name,booktime,type as status')
                ->order('booktime desc')
                ->select();
        return $data->toArray();
    }
    
    /**
     * @添加用户项目
     * @param string $name 患者姓名
     * @param int $sex 性别
     * @param string $age 年龄
     * @param int $phone 电话
     * @param int $booktime 预约时间
     * @param int $uid 用户id
     */
    public function addData($data){
        $saveType = 'ProjectUser.add';
        
        $return = $this::validate($saveType)->allowField(true)->save($data);
        
        return empty($this::getError())?['status'=>1,'data'=>$this->id]:['status'=>2,'msg'=>$this::getError()];
    }
    
    //修改预约时间
    public function editBookTime($id,$time){
        $data = $this::where(['id'=>$id])->update(['booktime'=>$time]);
        return $data;
    }
    
    //修改预约状态
    public function editType($id,$status){
        $data = $this::where(['id'=>$id])->update(['type'=>$status]);
        return $data;
    }
    
    /**
     * @删除
     */
    public function del($id){
        $data = $this::where(['id'=>$id])->update(['status'=>-1]);
        return $data;
    }
    /**
     * 修改器
     * 修改器的作用是可以在数据赋值的时候自动进行转换处理
     * 方法名规则：setNameAttr() ，Name：字段名
     */
    
    //注册时间
    public function setCreatetimeAttr(){
        return time();
    }
    
    public function setBooktimeAttr($value){
        return strtotime($value);
    }
    
    public function getImageAttr($value)
    {
        return empty($value)?'':$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$value;
    }
    
    
}
