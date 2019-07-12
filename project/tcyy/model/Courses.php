<?php
namespace app\tcyy\model;

class Courses extends Common {

    //自定义初始化
    protected function initialize()
    {
        parent::initialize();
    }

	//获取当前用户下的课程列表
    public function getCourseListByCondition($where){
        $data = $this::where($where) -> field('id,title') ->select();
        return empty($data)?[]:$data->toArray();
    }

    //保存课程信息
    public function saveCourseInfo($data){
        $data = $this::save($data);
        return $data?$this->id:false;
    }

}	