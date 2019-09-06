<?php
namespace app\tcyy\model;

use think\Db;

class CoursesUser extends Common {

    protected $name = 'courses_user';
    //类型转换
    protected $type = [
        'btimes' => 'timestamp:Y-m-d H:i:s'
    ];
    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->_collection = Db::name($this->name);
    }

	//获取当前用户购买列表
    public function getBuyerHistoryListByCondition($page,$count,$param,$sort=[' cu.cid desc ']){
        $where = [
            'cu.uid' => $param
        ];

        $data = $this -> _collection  ->alias('cu') -> where($where) -> join('tcyy_courses c','c.id = cu.cid','inner')->
        field('GROUP_CONCAT(cu.multiinfo) as multiinfo,cu.cid,c.title') -> page($page.','.$count)
            ->group('cu.uid,cu.cid')->order($sort)->select();
        return empty($data)?[]:$data;
    }

    //用户售卖记录列表
    public function getSellCourseListByCondition($page,$count,$uid,$sort=['cu.btimes desc']){
        $where = ['c.uid' => $uid];
        $data = $this ->_collection
            ->alias('cu')
            ->where($where)
            ->join('tcyy_courses c', ' c.id = cu.cid ', 'inner')
            ->field('cu.id,cu.cid,cu.uid,DATE_FORMAT(FROM_UNIXTIME(cu.btimes),\'%Y-%m-%d %h:%i:%s\') as btimes,cu.multiinfo,cu.amounts,c.title,cu.withdraw ')->page($page.','.$count)->order($sort)->select();
        return empty($data)?[]:$data;
//        return $this::where($where) -> field('') ->select();
    }

    //支付成功后保存购买课程视频记录
    public function saveCoureseByUserPaid($data){
        $rtn = $this::allowField(true)->save($data);
        $errors = $this::getError();
        return empty( $errors) ? ['status' => 1,'data' => $this['id']] : ['status' => 2,'msg' => $errors];
    }

    /**
     * 根据课程查询购买的视频记录 行转列
     * @param $cid
     */
    public function getBuyVideoIdsByCid($cid,$uid){
        return $this -> _collection -> field('GROUP_CONCAT(multiinfo) as multiinfo') -> where(['cid' => $cid,'uid' => $uid])-> find();
    }

    //根据课程编号获取购买者数量
    public function getBuyerNumByCid($cid){
        return $this -> where(['cid' => $cid]) -> count();
    }

    //根据课程编号获取购买者数量
    public function getBuyerNumByVid($vid){
        return $this -> where(['find_in_set('. $vid .',multiinfo']) -> count();
    }

    public function getWithdrawStatInfo($uid){
        $where = ['a.withdraw' => 0,'c.uid' => $uid];
        $data = $this -> _collection
            -> alias('a')
            -> where($where)
            -> join('tcyy_courses c', ' c.id = a.cid ', 'left')
            -> join('tcyy_courses_user b', ' a.id = b.id and b.withdraw=1 ', 'left')
            -> field('c.uid,IFNULL(sum(a.amounts),0) as nonwithdraw,IFNULL(sum(b.amounts),0) as withdrawed ') -> find();
        return empty($data)?[]:$data;
    }
}