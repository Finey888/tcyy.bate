<?php
namespace app\tcyy\controller;
use think\Request;

class Personaleducation extends Base {
	protected $model = null;

    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->model = new \app\tcyy\model\PersonalEducation();
    }

    /**
     * 保存个人教育经历
     */
    public function savePersonEducationExp(){
        $get = input('post.');
        if(empty($get['id'])){
            $data['rid'] = empty($get['rid'])? returnAjax([],'缺少简历编号参数',2):$get['rid'];
            $data['education'] = $get['education'];
            $data['schoolname'] = empty($get['schoolname'])? returnAjax([],'缺少学校名称参数',2):$get['rid'];
            $data['profession'] = empty($get['profession'])? returnAjax([],'缺少专业名称参数',2):$get['rid'];
            $data['entrancedate'] = empty($get['entrancedate'])? returnAjax([],'缺少入学日期参数',2):strtotime($get['entrancedate']);
            $data['graduatedate'] = empty($get['graduatedate'])? returnAjax([],'缺少毕业日期参数',2):strtotime($get['graduatedate']);
            $return = $this -> model -> saveData($data);
            if($return['status'] == 2){
                returnAjax([], $return['msg'],2);
            }

            $rtd = [
                'id' => $return['data']['id'],
                'rid' => $return['data']['rid'],
                'schoolname' => $return['data']['schoolname']
            ];
            returnAjax($rtd, '保存成功',1);
        }else{
            $return = $this -> model -> saveData($get,['id'=>$get['id']]);
            if($return['status'] == 2){
                returnAjax([], $return['msg'],2);
            }
            $rtd = [
                'id' => $return['data']['id'],
                'rid' => $return['data']['rid'],
                'schoolname' => $return['data']['schoolname']
            ];
            returnAjax($rtd, '更新成功',1);
}
    }

    public function getPersonalEducationDetail(){
        $get = input('post.');
        $id = empty($get['id'])? returnAjax([],'缺少主键编号参数',2):$get['id'];
        $data = $this -> model -> getById($id);
        returnAjax($data,'获取成功',1);
    }

}
