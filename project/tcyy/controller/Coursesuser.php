<?php
namespace app\tcyy\controller;
use think\Request;

class Coursesuser extends Common {
	protected $model = null;

    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this -> model = new \app\tcyy\model\CoursesUser();
    }

    /**
     * 获取当前用户售卖的课程记录
     */
   public function getUserSellRecords(){
       $get = input('post.');
       $page = empty($get['page'])?1:$get['page'];
       $limit = empty($get['limit'])?10:$get['limit'];
       $uid = $this->userData->id;
       $rtData =  $this -> model -> getSellCourseListByCondition($page,$limit,$uid);
       returnAjax(['data'=>$rtData,'page'=>$page],'获取成功',1);
   }

    /**
     * 获取当前用户购买的课程记录
     */
    public function getUserPurchaseRecords(){
        $get = input('post.');
        $page = empty($get['page'])?1:$get['page'];
        $limit = empty($get['limit'])?10:$get['limit'];
        $uid = $this->userData->id;
        $rtData =  $this -> model -> getBuyerHistoryListByCondition($page,$limit,$uid);
        returnAjax(['data'=>$rtData,'page'=>$page],'获取成功',1);
    }

    /**
     * 存储购买信息记录
     */
    public function storagePaidCourseInfo(){
        $get = input('post.');
        $data['cid'] = $get['cid'];
        $data['uid'] = $this->userData->id;
        $data['multiinfo'] = empty($get['multiinfo'])? returnAjax([],'缺少视频编号信息参数',2):$get['multiinfo'];
        $data['amounts'] = $get['amounts'];
        $data['btimes'] = strtotime(date('Y-m-d 00:00:00',time()));
        $result= $this -> model -> saveCoureseByUserPaid($data);
        if($result['status'] == 2){
            returnAjax([], '记录失败!!!',2);
        }
        returnAjax([], '记录成功',1);
    }
}
