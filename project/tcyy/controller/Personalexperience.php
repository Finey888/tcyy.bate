<?php
namespace app\tcyy\controller;
use think\Request;

class Personalexperience extends Common {
	protected $model = null;
	protected $resumeModel = null;

    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->model = new \app\tcyy\model\PersonalExperience();
        $this->resumeModel = new \app\tcyy\model\PersonalResume();
    }


    /**
     * 保存个人工作经历记录
     */
    public function savePersonalExperience(){
        $get = input('post.');
        if(empty($get['id'])){

            $uid= $this -> userData -> id;
            $where = ['uid' => $uid];
            $rtnResume = $this->resumeModel->getResumeByCondition($where);
            if(empty($rtnResume['id'])){
                returnAjax([], '没有对应简历信息',2);
            }
            $data['rid'] = $rtnResume['id'];
            $data['companyname'] = empty($get['companyname'])? returnAjax([],'缺少公司名称参数',2):$get['companyname'];
            $data['positions'] = empty($get['positions'])? '':$get['positions'];
            $data['entrancedate'] = empty($get['entrancedate'])? returnAjax([],'缺少入职日期参数',2):$get['entrancedate'];//strtotime($get['entrancedate']);
            $data['dimissiondate'] = empty($get['dimissiondate'])? returnAjax([],'缺少离职日期参数',2):$get['dimissiondate'];//strtotime($get['dimissiondate']);
            $data['workcontent'] = empty($get['workcontent'])? '':$get['workcontent'];
            $return = $this -> model -> saveData($data);
            if($return['status'] == 2){
                returnAjax([], $return['msg'],2);
            }
            $rtd=[
                'id' => $return['data']['id'],
                'companyname' => $return['data']['companyname']
            ];
            returnAjax($rtd, '新增成功',1);
        }else{
            $return = $this -> model -> saveData($get,['id'=>$get['id']]);
            if($return['status'] == 2){
                returnAjax([], $return['msg'],2);
            }

            $rtd=[
                'id' => $return['data']['id'],
                'companyname' => $return['data']['companyname']
            ];
            returnAjax($rtd, '更新成功',1);
        }


    }

    public function getPersonalExperienceDetail(){
        $get = input('post.');
        $id = empty($get['id']) ? returnAjax([],'缺少主键编号参数',2) : $get['id'];
        $data = $this -> model -> getById($id);
        returnAjax($data,'获取成功',1);
    }
}
