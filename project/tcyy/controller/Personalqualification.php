<?php
namespace app\tcyy\controller;
use think\Request;

class Personalqualification extends Common {
	protected $model = null;
	protected $resumeModel = null;

    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->model = new \app\tcyy\model\PersonalQualification();
        $this->resumeModel = new \app\tcyy\model\PersonalResume();
    }


    //保存浏览简历记录历史
    public function savePersonalQualification(){
        $get = input('post.');
        if(empty($get['id'])){
            $uid= $this -> userData -> id;
            $where = ['uid' => $uid];
            $rtnResume = $this->resumeModel->getResumeByCondition($where);
            if(empty($rtnResume['id'])){
                returnAjax([], '没有对应简历信息',2);
            }
            $data['rid'] = $rtnResume['id'];
//            $data['rid'] = empty($get['rid'])? returnAjax([],'缺少简历编号参数',2):$get['rid'];
            $data['qualifyname'] = empty($get['qualifyname'])? returnAjax([],'缺少证书名称参数',2):$get['qualifyname'];
            $data['qualifyurl'] = empty($get['qualifyurl'])? returnAjax([],'缺少证书URL参数',2):$get['qualifyurl'];
            $data['qualify_time'] = empty($get['qualify_time'])? returnAjax([],'缺少简历编号参数',2):strtotime(date('Y-m-d',$get['qualify_time']));
            $data['createtime'] = strtotime(date('Y-m-d',time()));

            $return = $this -> model -> saveQualification($data);

            if($return['status'] == 2){
                returnAjax([], $return['msg'],2);
            }

            $rtd=[
                'id'=>$return['data']['id'],
                'qualifyname'=>$return['data']['qualifyname']
            ];

            returnAjax($rtd, '增加成功',1);
        }else{
            $return = $this -> model -> saveQualification($get,['id'=>$get['id']]);
            if($return['status'] == 2){
            returnAjax([], $return['msg'],2);
            }

            $rtd=[
                'id' => $return['data']['id'],
                'qualifyname' => $return['data']['qualifyname']
            ];
            returnAjax($rtd, '更新成功',1);
        }

    }

    public function getPersonalQualificationDetail(){
        $get = input('post.');
        $id = empty($get['id']) ? returnAjax([],'缺少主键编号参数',2) : $get['id'];
        $data = $this -> model -> getById($id);
        returnAjax($data,'获取成功',1);
    }
}
