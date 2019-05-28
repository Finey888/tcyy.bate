<?php
namespace app\index\model;
use think\Db;
class Vip extends Common
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
    
    
    //分页数据
    public function getPageData(){
   
        $data = $this::field('id,name,mouth,price,status')->where(['status'=>['neq',-1]])->order('mouth desc')->select();

        return $data;
    }
    
    //修改状态
    public function setStatus($id,$status){
        $data = $this->where(['id'=>$id])->update(['status'=>$status]);
        return $data;
    }
    
    //根据ID获取数据
    public function getDataById($id){
        return $this::where(['id'=>$id])->find();
    }
    
    //保存数据
    public function saveData($data,$where=[]){
        $saveType = 'Vip.add';
        
        if(!empty($where)){$saveType = 'Vip.edit';}
        
        $return = $this::validate($saveType)->allowField(true)->save($data,$where);
        
        return $this::getError();
    }

    /**
     * 修改器
     * 修改器的作用是可以在数据赋值的时候自动进行转换处理
     * 方法名规则：setNameAttr() ，Name：字段名
     */
    
}
