<?php
namespace app\index\model;

class CoursesVideos extends Common {

    //类型转换
    protected $type = [
        'ctime' => 'timestamp:Y-m-d H:i:s'
    ];

    //自定义初始化
    protected function initialize()
    {
        parent::initialize();
    }

	//根据课程编号获取视频列表
    public function getVideoListByCid($param){
        $where = [
            'cid' => $param//,
            //'status' => '1'
        ];
        $data = $this :: where($where) -> field('id,title,prices,contents,urls,episodes,previews,views,ctime') ->select();
        return $data -> toArray();
    }


    public function reviewVideoById($id,$st)
    {
        return $this :: where(['id'=>$id])->update(['status' => $st]);
    }


    //课程视频分页数据查询
    public function getVideosByPage($page=1,$count=10,$where=[],$sort='v.ctime desc'){
        $data = $this ->alias('v')
            ->where($where)
            ->join('tcyy_courses c', ' c.id = v.cid ', 'left')
            ->field('c.id AS cid,c.title AS ctitle,c.gid,v.id AS vid,v.title AS vtitle,v.prices,v.ctime AS ctime,v.views ')->page($page.','.$count)->order($sort)->select();
        return $data;
    }



}	