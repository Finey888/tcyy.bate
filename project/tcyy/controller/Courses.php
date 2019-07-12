<?php
namespace app\tcyy\controller;
use think\Request;

class Courses extends Base {
	protected $model = null;

    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->model = new \app\tcyy\model\Courses();
    }


    //获取登录用户发布的课程列表
    public function getCoursesList(){
        $get = input('post.');
        $uid =  $this->userData->id;
        $where = ['uid' =>$uid ];

        $rtd = $this -> model -> getCourseListByCondition($where);

        returnAjax($rtd, '查询成功',1);
    }

}
