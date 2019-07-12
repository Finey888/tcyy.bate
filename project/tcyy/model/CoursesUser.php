<?php
namespace app\tcyy\model;

class CoursesUser extends Common {

    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }

	//获取当前用户购买列表
    public function getBuyerHistoryListByCondition($param){
        $where = [
            'uid' => $param
        ];
        return $this::where($where) -> field('id,btimes,multiinfo,amounts') ->select();
    }

    //用户售卖记录列表
    public function getSellCourseListByCondition(){
        return $this::where($where) -> field('uid,btimes,multiinfo,amounts') ->select();
    }

    //支付成功后保存购买课程视频记录
    public function saveCoureseByUserPaid($data){
        return $this::save($data);
    }
}