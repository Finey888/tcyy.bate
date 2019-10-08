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
        $this-> coursesOrder = new \app\index\model\CoursesUser();
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
            $where['cs.gid'] = ['eq', $get['group_id']];
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

    //获取课程订单列表信息
    public function queryCoursesOrdersList(){
        $get = input('get.');
        $page = empty($get['page'])?1:$get['page'];
        $count = empty($get['count'])?10:$get['count'];

        $where = [];

        //发布者名称
        if(!empty($get['publisher'])){
            $where['u.nickname'] = ['like', $get['publisher']];
        }
        //课程名称
        if(!empty($get['c_title'])){
            $where['c.title'] = ['like', '%'.$get['c_title'].'%'];
        }
        //订单状态
        if($get['o_withdraw'] != '-'){
            $where['cu.withdraw'] = ['eq', $get['o_withdraw']];
        }

        $data = $this -> courses -> getCoursesOrdersList($page,$count,$where);

        return json(['data'=>$data,'status'=>1,'msg'=>'获取数据成功']);
    }

    //获取课程订单列表信息
    public function ordersList(){
        $get = input('get.');
        $where = [];

        //发布者名称
        if(!empty($get['publisher'])){
            $where['u.nickname'] = ['like', $get['publisher']];
        }
        //课程名称
        if(!empty($get['c_title'])){
            $where['c.title'] = ['like', '%'.$get['c_title'].'%'];
        }
        //订单状态
        if(!empty($get['o_withdraw']) && ($get['o_withdraw'] != '-')){
            $where['cu.withdraw'] = ['eq', $get['o_withdraw']];
        }

        $countPage = $this -> courses -> getOrdersCount($where);

        $this -> assign('countPage',$countPage);
        return view('Courses/orders');
    }

    //手工提现
    public function withdrawOperation(){
        if(request()->isPost()){ $id = input('post.id'); }else{ return json(['status'=>-1,'msg'=>'未知数据']); }
        $return = $this->coursesOrder->ordersWithdrawing($id);

        return $return ? ['status'=>1,'msg'=>'提现成功'] : ['status'=>-1,'msg'=>'提现失败'];
    }
}
