<?php
namespace app\tcyy\model;

use think\Model;

class PersonalResumeViewLog extends Model {

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
    public function saveViewLog($data,$where){
        $this::allowField(true) -> save($data,$where);
        $errors = $this::getError();
        return empty( $errors)?['status'=>1,'data'=>$this->toArray()]:['status'=>2,'msg'=> $errors];
    }

    public function getViewLogByIds($where){
        return $this -> where($where) -> find();
    }

}	