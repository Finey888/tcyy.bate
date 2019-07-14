<?php
namespace app\tcyy\controller;
use think\Request;

class Courses extends Common {
	protected $model = null;
	protected $videoModel = null;
	protected $courseUser = null;

    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->model = new \app\tcyy\model\Courses();
        $this->videoModel = new \app\tcyy\model\CoursesVideos();
        $this->courseUser = new \app\tcyy\model\CoursesUser();
    }


    //获取登录用户发布的课程列表
    public function getCoursesList(){
        $get = input('post.');
        $uid =  $this->userData->id;
        $where = ['uid' =>$uid ];

        $rtd = $this -> model -> getCourseListByCondition($where);

        returnAjax($rtd, '查询成功',1);
    }


    /**
     * 视频发布流程
     * 1 - 当前用户下选择已经存在得课程列表 如果选择,保存视频即可
     * 2 - 如果未选择课程列表  输入相关课程、视频信息,先保存课程,再保存视频即可
     */
    public function saveCourseAndVideos(){
        $get = input('post.');
        $data['cid'] = empty($get['cid']);  //选择了课程下拉
        if(empty($get['cid'])){ //如果未选择课程,添加当前课程信息
            $data['gid'] = empty($get['gid'])? returnAjax([],'缺少课程分类参数',2):$get['gid'];
            $data['creattime'] = strtotime(date('Y-m-d 00:00:00',time()));
            $data['title'] = empty($get['ctitle'])? returnAjax([],'缺少课程标题参数',2):$get['ctitle'];
            $data['contents'] = empty($get['ccontents'])? returnAjax([],'缺少课程描述参数',2):$get['ccontents'];
            $data['ctype'] = $get['ctype'];
            $data['price'] = $get['price']; //课程单价=视频单价
            $data['oneprice'] = $get['oneprice'];  //买断价
            $data['uid'] = $this->userData->id;
            $return = $this -> model->saveCourseInfo($data);
            if(!$return){
                returnAjax([], '课程保存失败了!!!',2);
            }
            $data['cid'] = $return;
        }

        $videoData['cid'] = $data['cid'];
        $videoData['title'] = empty($get['vtitle'])? returnAjax([],'缺少视频标题参数',2):$get['vtitle'];
        $videoData['contents'] = $data['vcontents'];
        $videoData['urls'] = $data['urls'];
        $videoData['episodes'] = $data['episodes'];
        $videoData['ctime'] = strtotime(date('Y-m-d 00:00:00',time()));
        $result = $this -> videoModel->saveCourseVideoInfo($data);
        if($result['status'] == 2){
            returnAjax([], '发布视频失败!!!',2);
        }
        returnAjax([], '视频发布成功',1);
    }

    /**
     * 查看课程详情
     */
    public function coursesDetails(){
        $get = input('post.');
        $cid = empty($get['cid']);
        if(empty($cid)){
            returnAjax([],'缺少课程编号参数',2);
        }
       $cData = $this -> model -> getInfoById($cid);
       $vDataList = $this -> videoModel -> getVideoListByCid($cid);
       $videoIds = $this -> courseUser -> getBuyVideoIdsByCid($cid);
       $cData['videosList'] = $vDataList;
       $cData['videoIds'] = $videoIds;
       returnAjax($cData,'获取数据成功',1);exit();
    }

    /**
     * 搜索视频课程信息
     */
    public function search(){
        $get = input('post.');
        $page = empty($get['page'])?1:$get['page'];
        $limit = empty($get['limit'])?10:$get['limit'];
        $gid = empty($get['gid']);
        $ctime = empty($get['ctime']);
        $prices = empty($get['prices']);
        $where = [];
        if(!empty($gid)){
            $where['c.gid'] = ['eq',$gid];
        }
        $sort = '' ;
        if(!empty($ctime)){
            if($ctime == 'desc'){
                $sort = 'v.ctime desc';
            }else{
                $sort= 'v.ctime asc';
            }
        }
        if(!empty($prices)){
            if($prices == 'desc'){
                if($sort != '')
                    $sort = $sort.',v.prices desc';
                else
                    $sort = 'v.prices desc';
            }else{
                if($sort != '')
                    $sort = $sort.',v.prices asc';
                else
                    $sort = 'v.prices asc';
            }
        }

        $data = $this -> videoModel -> getVieosPageByCondition($page,$limit,$where,$sort);
        returnAjax($data,'获取成功',1);
    }
}
