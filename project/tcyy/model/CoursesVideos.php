<?php
namespace app\tcyy\model;

class CoursesVideos extends Common {

    //自定义初始化
    protected function initialize()
    {
        parent::initialize();
    }

	//获取视频列表
    public function getListByCid($param){
        $where = [
            'cid' => $param,
            'status' => '1'
        ];
        return $this :: where($where) -> field('id,title,prices,contents,urls') ->select();
    }

    //保存视频信息
    public function saveCourseVideInfo($data){
       return $this::allowField(true)->save($data);
    }
}	