<?php
namespace app\tcyy\model;
use think\Db;
class Sendmsg extends Common
{
    //新增时自动写入配置字段
    protected $insert = ['creattime','endTime']; 
    
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    /**
     * @保存数据
     */
    public function saveData($data){
        $saveType = 'Sendmsg.add';
        if(empty($data['id'])){
            $saveType = 'Sendmsg.edit';
        }
        $this::validate($saveType)->allowField(true)->save($data);
        return empty($this::getError())?['status'=>1,'data'=>$this->toArray()]:['status'=>2,'msg'=>$this::getError()];
    }
    
    /**
     * @根据电话号码查询有效的验证码发送记录
     * @param int $phone d电话
     * @param int $type 场景类型
     */
    public function getValidCodeByPhone($phone,$type){
        $data = $this::where(['phone'=>$phone,'type'=>$type,'status'=>1,'endTime'=>['gt',time()]])->find();
        return $data;
    }
    
    /**
     * @修改验证码状态
     * @param int $id ID
     */
    public function editStatus($id){
        $data = $this::allowField(true)->save(['status'=>2],['id'=>$id]);
        return $data;
    }
    
    public function setCreattimeAttr(){
        return time();
    }
    
    public function setEndTimeAttr(){
        return strtotime("+10 minute");
    }
    
}
