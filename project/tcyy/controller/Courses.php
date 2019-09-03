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


    //获取登录用户发布的课程下来列表
    public function getCoursesList(){
        $get = input('post.');
        $uid =  $this->userData->id;
        $where = ['uid' =>$uid ];

        $rtd = $this -> model -> getCourseListByCondition($where);

        returnAjax($rtd, '查询成功',1);
    }

    //获取登录用户发布的课程下来列表
    public function getCoursesListByCurrentUser(){
        $get = input('post.');
        $page = empty($get['page'])?1:$get['page'];
        $limit = empty($get['limit'])?10:$get['limit'];
        $uid =  $this->userData->id;
        $where = ['uid' => $uid ];
        $sort = 'creattime desc';
        $rtd = $this -> model -> queryCourseListByUser($page,$limit,$where,$sort);

        returnAjax(['data'=>$rtd,'page'=>$page], '查询成功',1);
    }


    /**
     * 视频发布流程
     * 1 - 当前用户下选择已经存在得课程列表 如果选择,保存视频即可
     * 2 - 如果未选择课程列表  输入相关课程、视频信息,先保存课程,再保存视频即可
     */
    public function saveCourseAndVideos(){
        $get = input('post.');
        if(empty($get['cid'])){ //如果未选择课程,添加当前课程信息
            $data['gid'] = empty($get['gid'])? returnAjax([],'缺少课程分类参数',2):$get['gid'];
            $data['creattime'] = strtotime(date('Y-m-d H:i:s',time()));
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
            $cid = $return;
        }else{
            $cid = $get['cid'];
            $coursesInfo = $this ->model ->getInfoById($cid);
            $data['price'] = $coursesInfo['price'];
        }

        $videoData['cid'] = $cid;
        $videoData['title'] = empty($get['vtitle'])? returnAjax([],'缺少视频标题参数',2):$get['vtitle'];
        $videoData['contents'] = empty($get['vcontents'])?'':$get['vcontents'];
        $videoData['urls'] = $get['urls'];
        $videoData['prices'] =  $data['price'];
        $videoData['episodes'] = $get['episodes'];
        $videoData['ctime'] = strtotime(date('Y-m-d H:i:s',time()));
        $result = $this -> videoModel->saveCourseVideoInfo($videoData);
        if($result['status'] == 2){
            returnAjax([], '发布视频失败!!!',2);
        }
        returnAjax([], '视频发布成功',1);
    }

    /**
     * 根据cid查看个人发布的课程详情
     */
    public function coursesDetails(){
        $get = input('post.');
        $cid = $get['cid'];
        if(empty($cid)){
            returnAjax([],'缺少课程编号参数',2);
        }
       $cData = $this -> model -> getInfoById($cid);
       $vDataList = $this -> videoModel -> getVideoListByCid($cid);
//       $videoIds = $this -> courseUser -> getBuyVideoIdsByCid($cid);
       $cData['videosList'] = $vDataList;
//       $cData['videoIds'] = $videoIds;
       returnAjax($cData,'获取数据成功',1);exit();
    }

    /**
     * 根据当前用户查看已购买的课程详情
     */
    public function purchaseCoursesDetailsByCid(){
        $get = input('post.');
        $cid = $get['cid'];
        if(empty($cid)){
            returnAjax([],'缺少课程编号参数',2);
        }
        $cData = $this -> model -> getInfoById($cid);
        $vDataList = $this -> videoModel -> getVideoListByCid($cid);

        $uid = $this->userData->id;
        $videoIds = $this -> courseUser -> getBuyVideoIdsByCid($cid,$uid);
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
        $gid = empty($get['gid'])?'':$get['gid'];
        $ctime = empty($get['ctime'])?'':$get['ctime'];
        $prices = empty($get['prices'])?'':$get['prices'];
        $isFree = empty($get['isFree'])?'':$get['isFree'];
        $keywords = empty($get['keywords'])?'':$get['keywords'];
        $where = [];
        if(!empty($gid)){
            $where['c.gid'] = ['eq',$gid];
        }

        $sort = 'v.ctime desc' ;
        if(!empty($ctime)){
            if($ctime == 'desc'){
                $sort = 'v.ctime desc';
            }else{
                $sort= 'v.ctime asc';
            }
        }
        if(!empty($prices)){
            if($prices == 'desc'){
               $sort = 'v.prices desc';
            }else{
               $sort = 'v.prices asc';
            }
        }
        if(!empty($isFree)){
            $where['c.price']=['eq',0];
        }
        if(!empty($keywords)){
             $where['c.title|v.title'] = ['like', '%'.$keywords.'%'];
        }

        $data = $this -> videoModel -> getVieosPageByCondition($page,$limit,$where,$sort);
//        $data = $this -> model -> getVideosPageByCondition($page,$limit,$where,$sort);

        returnAjax(['data'=>$data,'page'=>$page],'获取成功',1);
    }

    public function delCoursesById(){
        $get = input('post.');
        $cid = $get['cid'];
        if(empty($cid)){
            returnAjax([],'缺少课程编号参数',2);
        }
        $num = $this -> courseUser -> getBuyerNumByCid($cid);
        if($num == 0){
            returnAjax([],"有购买用户,不能删除该课程!",1);
        }
        $this -> model ->delCourse($cid);
        //如果课程能删除 ,将课程对应的视频全部删除掉
        $this -> videoModel -> deleteVideosByCid($cid);
        returnAjax([],"删除成功",1);
    }
}
