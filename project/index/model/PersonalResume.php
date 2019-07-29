<?php
namespace app\index\model;

class PersonalResume extends Common {

    //分页数据
    public function getPageData($page = 1,$count = 10,$where=[]){
        $data = $this::field('id,personname,birthday,education,jobstatus,expectregion,address,telephone,email,auditstatus,workexperience,jointime') -> where($where) -> page($page.','.$count) -> select();
        return $data;
    }

    //获取总条数
    public function getCount($where = []){
        return $this -> where($where) -> count();
    }

    //审核数据
    public function  auditDataById($id,$auditStatus){
        return $this::update(['id'=>$id,'auditstatus'=>$auditStatus]);
    }

    //根据主键查询简历信息
    public function getDataById($id){
        $data = $this::where(['id'=>$id])->find();
        return $data;
    }
}	