<?php
namespace app\tcyy\model;
use think\Db;
class CurriculumUser extends Common
{
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    /**
     * @获取详情
     */
    public function getDataFind($cid,$uid){
        $data = $this::where(['uid'=>$uid,'cid'=>$cid])->find();
        return empty($data)?[]:$data->toArray();
    }
    
    /**
     * @保存数据
     */
    public function saveData($cid,$uid){
        $data = $this::save(['uid'=>$uid,'cid'=>$cid,'times'=>time()]);
        return $data;
    }
    
}
