<?php
namespace app\index\model;
use think\Db;
class Member extends Common
{
    //新增时自动写入配置字段
    protected $insert = ['reg_time','last_edit_time']; 
    protected $update = ['last_login_ip','last_edit_time'];
    //类型转换
    protected $type = [
            'last_login_time' => 'timestamp:Y-m-d H:i:s'//写入时间戳，读取按照Y/m/d的格式来格式化输出。
        ];
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    public function AuthGroup()
    {
        return $this->belongsToMany('AuthGroup','AuthGroupAccess','group_id','uid');
    }
    
    //分页数据
    //->field('id,username,nickname,phone,last_login_time,last_login_ip,login_count,status')
    public function getPageData($page=1,$count=10,$where=['status'=>['neq',-1]]){
        return $this::with('AuthGroup')->field('id,username,nickname,phone,last_login_time,last_login_ip,login_count,status')->where($where)->page($page.','.$count)->select();
    }
    
    //获取总条数
    public function getCount($where=['status'=>['neq',-1]]){
        return $this->where($where)->count();
    }
    
    //修改状态
    public function setStatus($id,$status){
        $data = $this->where(['id'=>$id])->update(['status'=>$status]);
        return $data;
    }
    
    //根据ID获取数据
    public function getDataById($id){
        return $this::with('AuthGroup')->where(['id'=>$id])->field('id,username,nickname,phone,status,remarks')->find();
    }
    
    //保存数据
    public function saveData($data,$where=[]){
        $saveType = 'Member.add';
        
        if(!empty($where)){$saveType = 'Member.edit';}
        
        $return = $this::validate($saveType)->allowField(true)->save($data,$where);
        
        if($return == false){ return $this::getError(); }
        
        //插入权限
        $groupWhere=[];
        $groupData=[];
        if(!empty($where)){
            //修改
            $groupWhere['uid'] = $where['id'];
            $groupData=['group_id'=>$data['group_id']];
        }else{
            //新增
            $groupData = ['uid'=>$this->getLastInsID(),'group_id'=>$data['group_id']];
        }
        
        $GroupModel = new \app\index\model\AuthGroupAccess();
        
        $groupReturn = $GroupModel->saveData($groupData, $groupWhere);
        
        return $groupReturn;
    }

    //根据ID删除数据
    public function delById($id){
        return $this::update(['id'=>$id,'status'=>-1]);
    }

    public function getMemberMsg($username)
    {
        $res = $this->where('username',$username)->find();
        return $res;
    }
    
    /**
     * 修改器
     * 修改器的作用是可以在数据赋值的时候自动进行转换处理
     * 方法名规则：setNameAttr() ，Name：字段名
     */
    
    //密码修改器
    public function setPasswordAttr($value){
        return md5(config('password_str')[0].$value);
    }
    
    //设置字段值
    public function setRegTimeAttr(){
        return time();
    }
    //设置字段值
    public function setLastEditTimeAttr(){
        return time();
    }
}
