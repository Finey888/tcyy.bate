<?php
namespace app\tcyy\model;

use think\Db;

class PersonalCompany extends Common {

    protected $name = 'personal_company';

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

    public function addData($data,$where=[]){
//        if(empty($where)){
//            $saveType = 'Personalcompany.add';
//        }else{
//            $saveType = 'Personalcompany.edit';
//        }

        $return = $this :: allowField(true) -> save($data,$where);
        $errors = $this::getError();
        return empty( $errors) ? ['status' => 1,'data' => $this->toArray()] : ['status' => 2,'msg' => $errors];
    }

    public function getDataById($id){
        $data = $this :: where(['id' => $id]) -> find();
        return empty($data) ? [] : $data;
    }

	//原生sql查询,查询返回投递给公司发布的职位的简历列表数据
    public function queryList($uid,$page,$limit){
        $page = ($page - 1) * $limit;
        $querySql = 'SELECT a.jid,a.rid,a.personname,a.arrivaltime,a.telephone,a.address,a.delivertime,b.professional FROM (SELECT rsm.personname,rsm.arrivaltime,rsm.telephone,rsm.address,dl.delivertime,dl.jid,dl.rid  FROM tcyy_personal_deliver dl , tcyy_personal_resume rsm WHERE dl.rid = rsm.id)a' .
            ' LEFT JOIN  (SELECT pt.id,pt.professional,company.uid FROM tcyy_personal_company company,tcyy_personal_position pt WHERE company.id = pt.cid )b ON a.jid = b.id WHERE b.uid = ? limit '.$page.','.$limit;
        return $this -> query($querySql,[$uid]);

    }

    /**
     * 根据登录vip用户查询浏览简历历史记录
     * @param $uid
     */
    public function queryViewResumeLogs($uid,$collectSign,$paginationStr){
        $querySql = 'SELECT rsm.personname,rsm.birthday,rsm.expectregion,rsm.address,rsm.telephone,rsm.arrivaltime,rsm.sex,vl.rid,vl.viewtime FROM tcyy_personal_resume rsm,tcyy_personal_resume_view_log vl WHERE rsm.id = vl.rid and vl.uid = ?';
        if(!empty($collectSign)){
            $querySql .= ' and vl.iscollect = ?';
            return $this -> query($querySql.$paginationStr,[$uid,$collectSign]);
        }
        return $this -> query($querySql.$paginationStr,[$uid]);
    }

    public function getCompanyByCondition($where){
        $data = $this :: where($where) -> find();
        return empty($data) ? [] : $data;
    }
}	