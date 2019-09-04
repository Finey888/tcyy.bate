<?php
namespace app\tcyy\model;

class CoursesWatchLog extends Common {

    //类型转换
    protected $type = [
        'vtime' => 'timestamp:Y-m-d'
    ];

    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }

	//列表
    public function queryWatchLogList($uid,$page,$count,$sort=['v.vtime desc']){
        $where = [
            'v.uid' => $uid
        ];
        return $this::alias("v")  ->join('tcyy_courses c', ' c.id = v.cid ', 'inner')
             ->join('tcyy_courses_videos vs', ' vs.id = v.vid ', 'inner') -> where($where) -> field('c.title as ctitle,vs.title as vtitle,vs.previews,v.id,v.cid,v.vid,v.vtime') ->page($page.','.$count)->order($sort) ->select();
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