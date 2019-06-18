<?php
namespace app\tcyy\model;

class PersonalQualification extends Common {

    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }

    /**
     * 保存/更新证书信息
     * @param $qualificationData
     * @return false|int
     */
    public function saveQualification($qualificationData,$where=[]){
        $return = $this -> allowField(true) -> save($qualificationData,$where);
        $eror = $this::getError();
        return empty($eror) ? ['status' => 1 ,'data' => $this -> toArray()] : ['status' => 2,'msg' => $eror];
    }


    /**
     * 根据主键删除操作
     * @param $id
     * @return PersonalQualification
     */
    public function del($id){
        return $this::where('id',  $id) -> update(['isdel' => 1]);
    }

    /**
     * 根据条件查询返回列表
     * @param $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getQualificationList($where){
        $data = $this::where($where)->select();
        return empty($data) ? [] : $data -> toArray();
    }
}