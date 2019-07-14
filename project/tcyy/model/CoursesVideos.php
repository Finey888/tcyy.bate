<?php
namespace app\tcyy\model;

use think\Db;

class CoursesVideos extends Common {

    protected $name = 'courses_videos';

    //自定义初始化
    protected function initialize()
    {
        parent::initialize();
        $this->_collection = Db::name($this->name);
    }

	//获取视频列表
    public function getVideoListByCid($param){
        $where = [
            'cid' => $param,
            'status' => '1'
        ];
        $data = $this :: where($where) -> field('id,title,prices,contents,urls,views,ctime') ->select();
        return $data -> toArray();
    }

    //保存视频信息
    public function saveCourseVideoInfo($data){
       $sv = $this::allowField(true)->save($data);
       $errors = $this::getError();
        return empty( $errors) ? ['status' => 1,'data' => $this->toArray()] : ['status' => 2,'msg' => $errors];
    }

    public function updateVideoById($id)
    {
        return $this :: where(['id'=>$id])->update(['views = views + 1']);
    }


    public function getVieosPageByCondition($page=1,$count=10,$where=[],$sort='v.ctime desc'){
        $data = $this ->_collection
            ->alias('v')
            ->where($where)
            ->join('tcyy_courses c', ' c.id = v.cid ', 'left')
            ->field('c.id AS cid,c.title AS ctitle,c.gid,v.id AS vid,v.title AS vtitle,v.prices,FROM_UNIXTIME(v.ctime) AS ctime,v.views ')->page($page.','.$count)->order($sort)->select();
        return $data;
    }



}	