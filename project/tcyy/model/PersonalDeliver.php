<?php
namespace app\tcyy\model;

use think\Model;

class PersonalDeliver extends Common {
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }

    /**
     * 保存简历投递职位数据
     * @param $data
     */
    public function saveDeliverData($data,$where=[]){
        $this::allowField(true) -> save($data,$where);
        $errors = $this::getError();
        return empty( $errors) ? ['status' => 1,'data' => $this->toArray()] : ['status' => 2,'msg' => $errors];
    }

    /**
     * 根据条件查询简历投递数据
     * @param $where
     * @return array|false|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getDeliverData($where){
        return $this -> where($where) -> find();
    }

}	