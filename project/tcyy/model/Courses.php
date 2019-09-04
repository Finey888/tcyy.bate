<?php
namespace app\tcyy\model;

class Courses extends Common {

    //类型转换
    protected $type = [
        'creattime' => 'timestamp:Y-m-d H:i:s',
        'ctime' => 'timestamp:Y-m-d H:i:s'
    ];

    //自定义初始化
    protected function initialize()
    {
        parent::initialize();
        $this -> videoModel = new \app\tcyy\model\CoursesVideos();
    }

    public function UserInfo()
    {
        return $this->hasOne('UserInfo','uid','uid');
    }

    public function CoursesVideos()
    {
        return $this-> hasMany('CoursesVideos','cid');
    }


    //获取当前用户下的课程列表
    public function getCourseListByCondition($where){
        $data = $this::where($where) -> field('id,title') ->select();
        return empty($data)?[]:$data->toArray();
    }

    public function queryCourseListByUser($page,$count,$where,$sort){
        $data = $this::where($where) -> field('id,title,ctype,gid,price,creattime,oneprice') ->page($page.','.$count) ->order($sort) ->select();
        return empty($data)?[]:$data;
    }

    //保存课程信息
    public function saveCourseInfo($data){
        $this::allowField(true) -> save($data);
        return $this;
    }

    //根据主键查看详情
    public function getInfoById($id){
        $data = $this :: where(['id' => $id]) -> find();
        return $data;
    }


    public function getVideosPageByCondition($page=1,$count=10,$where=[],$sort){
        $data = $this::with(['UserInfo' => function($query){
                        $query -> field('id,uid,nickname,headurl');
                        },'CoursesVideos' => function($query){
                            $query -> field('cid,urls,views,status,ctime');
                        }])
            -> where($where)
          ->page($page.','.$count)->order($sort)->select();
        return empty($data)?[]:$data;
    }

    //删除课程信息
    public function delCourse($id){
        return $this::where('id',  $id) -> update(['status' => -1]);
    }
}