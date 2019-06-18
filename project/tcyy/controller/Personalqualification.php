<?php
namespace app\tcyy\controller;
use think\Request;

class Personalqualification extends Base {
	protected $model = null;

    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->model = new \app\tcyy\model\PersonalQualification();
    }


    //保存浏览简历记录历史
    public function savePersonalQualification(){
        $get = input('post.');
        if(empty($get['id'])){
            $data['rid'] = empty($get['rid'])? returnAjax([],'缺少简历编号参数',2):$get['rid'];
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
                'professional'=>$return['data']['professional'],
                'region'=>$return['data']['region']
            ];

            returnAjax($rtd, '增加成功',1);
        }else{
            $return = $this -> model -> saveQualification($get,['id'=>$get['id']]);
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


    public function collectPersonalResume(){
        $get = input('post.');
        if(empty($get['rid'])){
            returnAjax([],'无对应收藏个人简历编号参数',2);
        }
        $data['rid'] = $get['rid'];
        $data['uid'] =  $this -> userData -> id;
        $this->model->collectResumeInfo($data);
        returnAjax([],"收藏成功",1);
    }
}
