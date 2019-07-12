<?php
namespace app\tcyy\model;

class CoursesWatchLog extends Common {

    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }

	//列表
    public function queryWatchLogList($param){
        $where = [
            'uid' => $param
        ];
        return $this::where($where) -> field('id,vid,vtime') ->select();
    }

    //保存观看日志信息
    public function saveWatchLog($data){
        return $this::allowField(true)->save($data);
    }
}	