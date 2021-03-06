<?php
namespace app\tcyy\model;

class PersonalResume extends Common {

    //根据主键查询简历信息
    public function getDataById($id){
        $data = $this :: join('tcyy_user_info u', ' u.uid = tcyy_personal_resume.uid ', 'left') -> where(['tcyy_personal_resume.id'=>$id])->find();
        return $data;
    }

    //保存简历信息
    public function saveData($data,$where=[]){
        $return = $this::allowField(true)->save($data,$where);
        $errors = $this::getError();
        return empty( $errors)?['status'=>1,'data'=>$this->toArray()]:['status'=>2,'msg'=> $errors];
    }

    public function UserInfo()
    {
        return $this->hasOne('UserInfo','uid');
    }

    //分页查询简历信息
    public function getPageData($page=1,$count=10,$where=[]){
        $data = $this::join('tcyy_user_info u', ' u.uid = tcyy_personal_resume.uid ', 'left') ->where($where)
                -> page($page.','.$count)
                -> field('tcyy_personal_resume.id,personname,birthday,education,jobstatus,expectregion,telephone,intentposition,workexperience,u.headurl')
                -> order('refreshtime desc')
                -> select();
        return $data -> toArray();
    }

    //逻辑删除
    public function  delResumeById($id){
        $data = $this::where(['id' => $id])->update(['isdel' => 1]);
        return $data;
    }

    public function getResumeByCondition($where){
        $data = $this::where($where)->find();
        return $data;
    }


    public function queryCompanyAndResumeInfo($uid){
//        $data = $this -> alias("rsm") -> join('tcyy_personal_company cmp',' rsm.uid = cmp.uid ','left') -> field('rsm.regionid AS rregionid,rsm.expectregion,rsm.address AS raddress,cmp.regionid AS cregionid,cmp.region,cmp.address AS caddress')
//            ->where(["rsm.uid" => $uid]) -> find();
        $querySql = 'SELECT u.uid,rsm.regionid AS rregionid,rsm.expectregion,rsm.address AS raddress,cmp.regionid AS cregionid,cmp.region,cmp.address AS caddress FROM tcyy_user_info u LEFT JOIN tcyy_personal_resume rsm ON u.uid = rsm.uid '.
                    'LEFT JOIN tcyy_personal_company cmp ON u.uid = cmp.uid WHERE u.uid = ?';
        $data = $this -> query($querySql,[$uid]);
        return $data;
    }
}	