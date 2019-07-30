<?php
namespace app\tcyy\model;

use think\Db;
use think\Model;

class PersonalResumeViewLog extends Common {

    protected $name = 'personal_resume_view_log';

    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->_collection = Db::name($this->name);
    }

    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }

    /**
     * 企业会员收藏简历
     * @param array $params
     * @return $this
     */
    public function collectResumeInfo($params=[]){
        return $this -> where(['rid' => $params['rid'],'uid' => $params['uid']])->update(['iscollect' => 1]);
    }

    /**
     * 保存查看日志记录
     * @param $data
     */
    public function saveViewLog($data,$where=[]){
        $this::allowField(true) -> save($data,$where);
        $errors = $this::getError();
        return empty( $errors)?['status'=>1,'data'=>$this->toArray()]:['status'=>2,'msg'=> $errors];
    }

    /**
     * 根据条件查询查看简历历史记录
     * @param $where
     * @return array|false|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getViewLogByIds($where){
        return $this -> where($where) -> find();
    }

    /**
     * 根据登录vip用户查询浏览简历历史记录
     * @param $uid
     */
    public function queryViewResumeLogs($collectSign,$page=1,$count=10,$where=[]){
        if(!empty($collectSign)) {
            $where = ['vl.iscollect' => $collectSign];
        }
        $data = $this -> _collection
            -> alias('vl')
            -> where($where)
            -> join('tcyy_personal_resume rsm', ' rsm.id = vl.rid ', 'inner')
            -> join('tcyy_user_info u', ' u.uid = vl.uid ', 'inner')
            -> field('rsm.id,rsm.personname,rsm.intentposition,rsm.education,rsm.workexperience,rsm.telephone,rsm.arrivaltime,rsm.sex,vl.rid,DATE_FORMAT(FROM_UNIXTIME(vl.viewtime),\'%Y-%m-%d\') as viewtime,u.headurl ')->page($page.','.$count)->order('vl.viewtime desc')->select();
        return $data;

//        $querySql = 'SELECT  FROM ,tcyy_personal_resume_view_log vl WHERE  and vl.uid = ? ';
//        if(!empty($collectSign)){
//            $querySql .= ' and vl.iscollect = ? ';
//            return $this -> query($querySql.$paginationStr,[$uid,$collectSign]);
//        }
//        return $this -> query($querySql.$paginationStr,[$uid]);
    }

}	