<?php
namespace app\tcyy\model;

class PersonalResume extends Common {
    //根据主键查询职位信息
    public function getDataById($id){
        $data = $this::where(['id'=>$id])->find();
        return $data;
    }

    //保存职位信息
    public function saveData($data,$where=[]){
        $return = $this::allowField(true)->save($data,$where);
        $errors = $this::getError();
        return empty( $errors)?['status'=>1,'data'=>$this->toArray()]:['status'=>2,'msg'=> $errors];
    }

    //分页查询简历信息
    public function getPageData($page=1,$count=10,$where=[]){
        $data = $this::where($where)
                -> page($page.','.$count)
                -> field('id,personname,birthday,education,jobstatus,expectregion,telephone,intentposition')
                -> order('refreshtime desc')
                -> select();
        return $data -> toArray();
    }

    //逻辑删除
    public function  delPostionById($id){
        $data = $this::where(['id' => $id])->update(['isdel' => 1]);
        return $data;
    }

}	