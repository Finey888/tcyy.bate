<?php
namespace app\index\controller;

use think\Request;

class Courses extends Common {

    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this -> courses = new \app\index\model\Courses();
        $this-> courseVideos = new \app\index\model\CoursesVideos();
        $this-> groupModel = new \app\index\model\Group();
    }

    //默认课程首页入口
    public function index(){
        $get = input('get.');
        $where = ['status' => 1];

        $group = $this->groupModel->getAllData();
        $this->assign('group', $group);

        //分类id
        if(!empty($get['group_id'])){
            $where['gid'] = ['eq', $get['group_id']];
        }
        //课程名称
        if(!empty($get['c_title'])){
            $where['title'] = ['like', '%'.$get['c_title'].'%'];
        }

        $countPage = $this -> courses -> getCoursesCount($where);
        $this -> assign('countPage',$countPage);
        return view('Courses/index');
    }

    //获取课程列表
    public function getList(){
        $get = input('get.');
        $page = empty($get['page'])?1:$get['page'];
        $count = empty($get['count'])?10:$get['count'];

        $where = ['cs.status' => 1];

        //分类id
        if(!empty($get['group_id'])){
            $where['cs.group_id'] = ['eq', $get['group_id']];
        }
        //课程名称
        if(!empty($get['c_title'])){
            $where['cs.title'] = ['like', '%'.$get['c_title'].'%'];
        }

        $data = $this -> courses -> getCoursePageListByCondition($page,$count,$where);

        return json(['data'=>$data,'status'=>1,'msg'=>'获取数据成功']);
    }

    //查询课程对应视频列表详情
    public function viewDetails(){
        if(request()->isGet()){
            $get = input('get.');
        }else{
            return json(['status'=>-1,'msg'=>'未知数据']);
        }

        if(empty($get['id'])){
            return json(['status'=>-1,'msg'=>'未知数据']);
        }
        $id = $get['id'];

        $data = $this -> courses ->getCourseInfoById($id);
        $vData = $this-> courseVideos -> getVideoListByCid($id);
        $this->assign('data',$data);
        $this->assign('vData',$vData);
        return view('Courses/details');
    }
}
