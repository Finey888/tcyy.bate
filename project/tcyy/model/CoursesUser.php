<?php
namespace app\tcyy\model;

use think\Db;
use think\Model;

class CoursesUser extends Model {

    protected $name = 'courses_user';

    //自定义初始化
//    protected function initialize()
//    {
//        //需要调用`Model`的`initialize`方法
//        parent::initialize();
//        $this->_collection = Db::name($this->name);
//    }
    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->_collection = Db::name($this->name);
    }

	//获取当前用户购买列表
    public function getBuyerHistoryListByCondition($page,$count,$param,$sort=[' btimes desc ']){
        $where = [
            'uid' => $param
        ];

        $data = $this::where($where) -> field('id,FROM_UNIXTIME(btimes) as btimes,multiinfo,amounts') -> page($page.','.$count)->order($sort)->select();
        return empty($data)?[]:$data->toArray();
    }

    //用户售卖记录列表
    public function getSellCourseListByCondition($page,$count,$uid,$sort=['cu.btimes desc']){
        $where = ['c.uid' => $uid];
        $data = $this ->_collection
            ->alias('cu')
            ->where($where)
            ->join('tcyy_courses c', ' c.id = cu.cid ', 'left')
            ->field('cu.id,cu.cid,cu.uid,FROM_UNIXTIME(cu.btimes) as btimes,cu.multiinfo,cu.amounts ')->page($page.','.$count)->order($sort)->select();
        return empty($data)?[]:$data->toArray();
//        return $this::where($where) -> field('') ->select();
    }

    //支付成功后保存购买课程视频记录
    public function saveCoureseByUserPaid($data){
        $rtn =$this::save($data);
        $errors = $this::getError();
        return empty( $errors) ? ['status' => 1,'data' => $rtn->id] : ['status' => 2,'msg' => $errors];
    }

    /**
     * 根据课程查询购买的视频记录 行转列
     * @param $cid
     */
    public function getBuyVideoIdsByCid($cid,$uid){
        $this -> _collection -> field('GROUP_CONCAT(multiinfo) as multiinfo') -> where(['cid' => $cid,'uid' => $uid])-> find();
    }
}