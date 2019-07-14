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
    public function queryWatchLogList($uid,$page,$count,$sort=['vtime desc']){
        $where = [
            'uid' => $uid
        ];
        return $this::where($where) -> field('id,vid,vtime') ->page($page.','.$count)->order($sort) ->select();
    }

    //保存观看日志信息
    public function saveWatchLog($data){
        return $this::allowField(true)->save($data);
    }

    /**
     * 判断当前用户当日有无观看记录
     * @param $where
     * @return int|string
     */
    public function existViewByDate($where){
        return $this::where($where) -> field('id,vid,vtime') ->count();
    }
}	