<?php
namespace app\index\model;
use think\Db;
class User extends Common
{
    //类型转换
    protected $type = [
            'regtime' => 'timestamp:Y-m-d H:i:s',//写入时间戳，读取按照Y/m/d的格式来格式化输出。
            'last_login_time' => 'timestamp:Y-m-d H:i:s',//写入时间戳，读取按照Y/m/d的格式来格式化输出。
            'vip_end_date'=>'timestamp:Y-m-d'
        ];
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    
    public function UserInfo()
    {
        return $this->hasOne('UserInfo','uid')->setEagerlyType(0);
    }
    
    //分页数据
    public function getPageData($page=1,$count=10,$where=['status'=>['neq',-1]],$sort='id desc'){
   
        $data = $this::field('id,status,last_login_time,is_vip,regtime,login_count,type,vip_end_date')->with(['UserInfo'=>function($query) {$query->withField("uid,nickname,phone,nickname,sex,headurl");}])->where($where)->page($page.','.$count)->order($sort)->select();

        return $data;
    }
    
    //获取总条数
    public function getCount($where=['status'=>['neq',-1]]){
        return $this::with('UserInfo')->where($where)->count();
    }
    
    //今日新增人数
    public function getNewCount(){
        $times = strtotime(date('Y-m-d 23:59:59',time()));
        $btime = strtotime(date('Y-m-d 00:00:00',time()));
        return $this::where('status','<>',-1)->where('regtime','elt',$times)->where('regtime','>=',$btime)->count();
    }
    
    //修改状态
    public function setStatus($id,$status){
        $data = $this->where(['id'=>$id])->update(['status'=>$status]);
        return $data;
    }
    
    //修改讲师状态
    public function setType($id,$status){
        $data = $this->where(['id'=>$id])->update(['type'=>$status]);
        return $data;
    }
    
     //修改VIP到期时间
    public function setVipDate($id,$enddate){
        $return = $this::validate('user.vipedit')->allowField(true)->update(['vip_end_date'=> strtotime($enddate)],['id'=>$id]);
        return $this::getError(); 
    }
    
    //根据ID获取数据
    public function getDataById($id){
        return $this::with('UserInfo')->where(['user.id'=>$id])->find();
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

    /**
     * 修改器
     * 修改器的作用是可以在数据赋值的时候自动进行转换处理
     * 方法名规则：setNameAttr() ，Name：字段名
     */
    
}
