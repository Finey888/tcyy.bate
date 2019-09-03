<?php
namespace app\tcyy\controller;
use think\Request;

class Coursesvideos extends Common {
	protected $videoModel = null;
	protected $logModel = null;

    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this -> videoModel = new \app\tcyy\model\CoursesVideos();
        $this -> logModel = new \app\tcyy\model\CoursesWatchLog();
        $this-> courseUser = new \app\tcyy\model\CoursesUser();
    }

    /**
     * 存储观看记录
     * 更新视频点击数量
     */
    public function storageViewVideoRecord(){
        $get = input('post.');
        $vid = $get['vid'];
        $vtime = strtotime(date('Y-m-d',time()));
        $uid = $this -> userData -> id;
        $where= ['uid' => $uid,'vtime'=>$vtime];
        $existCurVideos = $this -> videoModel -> existsUserVideos($uid,$vid);
        if($existCurVideos > 0) returnAjax([],'该视频为当前用户发布,无需记录',1);
        $existNum = $this -> logModel ->existViewByDate($where);
        if($existNum == 0){
            $data['vid'] = $vid;
            $data['vtime'] = $vtime;
            $data['uid']=$uid;
            $this -> logModel -> saveWatchLog($data);

            $this -> videoModel -> updateVideoById($vid);
            returnAjax([],'记录成功',1);
        }
        returnAjax([],'记录成功',1);
    }

    /**
     * 获取当前用户观看记录
     */
    public function queryViewVideoRecords(){
        $get = input('post.');
        $page = empty($get['page'])?1:$get['page'];
        $limit = empty($get['limit'])?10:$get['limit'];
        $uid = $this -> userData -> id;
        $data = $this ->logModel->queryWatchLogList($uid,$page,$limit);
        returnAjax(['data'=>$data,'page'=>$page],'获取成功',1);
    }

    public function deleteVideoInfo(){
        $get = input('post.');
        $vid = $get['vid'];
        $cid = $get['cid'];
        if(empty($ids)){
            returnAjax([],'缺少参数',2);
        }
        $num = $this -> courseUser -> getBuyerNumByVid($vid);
        if($num == 0){
            returnAjax([],"有购买用户,不能删除该课程对应的视频!",1);
        }

        $this -> videoModel ->deleteVideosByIds ($vid);
        returnAjax([],'删除成功',1);

    }
}
