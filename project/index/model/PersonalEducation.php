<?php
namespace app\index\model;

class PersonalEducation extends Common {
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }

    /**
     * 根据条件查询教育经历列表
     * @param $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function queryEduList($where){
        $data = $this :: where($where) -> select();
        return empty($data)?[] :$data -> toArray();
    }

    /**
     * 根据主键编号查询教育信息
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getById($id){
        $data = $this -> where(['id' => $id]) -> find();
        return empty($data)?[] :$data;
    }
}	