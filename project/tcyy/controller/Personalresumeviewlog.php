<?php
namespace app\tcyy\controller;
use think\Request;

class Personalresumeviewlog extends Common {
	protected $model = null;
	protected $resumeModel = null;

    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this -> model = new \app\tcyy\model\PersonalResumeViewLog();
        $this -> resumeModel = new \app\tcyy\model\PersonalResume();
    }


    //保存浏览简历记录历史
    public function savePersonalViewResumeLog(){
        $get = input('post.');
        $data['rid'] = empty($get['rid'])? returnAjax([],'缺少简历编号参数',2):$get['rid'];
        $data['viewtime'] = strtotime(date('Y-m-d',time()));
        $data['uid'] =  $this->userData->id;

        $where = ['rid'=>$get['rid'],'uid'=>$data['uid']];

        //先判断有无数据
        $haveSth = $this->model->getViewLogByIds($where);
        //有的话,更新,无的话增加
        if(empty($haveSth)) {
            $return = $this -> model -> saveViewLog($data);
        }else{
            $return = $this -> model -> saveViewLog($data,$where);
        }

        if($return['status'] == 2){
            returnAjax([], $return['msg'],2);
        }

        $rtd=[
            'id'=>$return['data']['id'],
            'rid'=>$return['data']['rid']
        ];

        returnAjax($rtd, '保存成功',1);
    }

    //收藏简历
    public function collectPersonalResume(){
        $get = input('post.');
        if(empty($get['rid'])){
            returnAjax([],'无对应收藏个人简历编号参数',2);
        }
        $data['rid'] = $get['rid'];
        $data['uid'] =  $this -> userData -> id;
        //收藏简历不能收藏自己的简历
        $rt = $this -> resumeModel -> getResumeByCondition(['uid'=>$data['uid'],'id' => $data['rid']]);
        if(!empty($rt)){
            returnAjax([], '不能收藏自己的简历',2);
        }

        $this->model->collectResumeInfo($data);
        returnAjax([],"收藏成功",1);
    }
}
