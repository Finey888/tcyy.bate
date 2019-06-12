<?php
namespace app\tcyy\model;

class Area extends Common {

    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }

	//列表
    public function getListByParentId($param){
        $where = [
            'upid' => $param
        ];
        return $this::where($where) -> field('id,name') ->select();
    }

}	