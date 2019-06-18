<?php
namespace app\tcyy\controller;
use think\Request;

class Personalexperience extends Base {
	protected $model = null;

    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->model = new \app\tcyy\model\PersonalExperience();
    }


    /**
     * 保存个人工作经历记录
     */
    public function savePersonalExperience(){
        $get = input('post.');
        if(empty($get['id'])){
            $data['rid'] = empty($get['rid'])? returnAjax([],'缺少简历编号参数',2):$get['rid'];
            $data['companyname'] = empty($get['companyname'])? returnAjax([],'缺少公司名称参数',2):$get['companyname'];
            $data['positions'] = empty($get['positions'])? returnAjax([],'缺少职位参数',2):$get['positions'];
            $data['entrancedate'] = empty($get['entrancedate'])? returnAjax([],'缺少入职日期参数',2):strtotime($get['entrancedate']);
            $data['dimissiondate'] = empty($get['dimissiondate'])? returnAjax([],'缺少离职日期参数',2):strtotime($get['dimissiondate']);
            $data['workcontent'] = empty($get['workcontent'])? returnAjax([],'缺少工作描述参数',2):strtotime($get['workcontent']);
            $return = $this -> model -> save($data);
            if($return['status'] == 2){
                returnAjax([], $return['msg'],2);
            }
            $rtd=[
                'id' => $return['data']['id'],
                'companyname' => $return['data']['companyname']
            ];
            returnAjax($rtd, '新增成功',1);
        }else{
            $return = $this -> model -> save($get,['id'=>$get['id']]);
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
