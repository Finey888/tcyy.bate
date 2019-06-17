<?php
namespace app\tcyy\controller;
use think\Request;

class Personalresumeviewlog extends Base {
	protected $model = null;

    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->model = new \app\tcyy\model\PersonalResumeViewLog();
    }


    //保存浏览简历记录历史
    public function savePersonalResume(){
        $get = input('post.');
        $data['rid'] = empty($get['rid'])? returnAjax([],'缺少简历编号参数',2):$get['rid'];
        $data['viewtime'] = strtotime(date('Y-m-d',time()));
        $data['uid'] =  $this->userData->id;

        $where = ['rid'=>$get['rid'],'uid'=>$data['uid']];

        //先判断有无数据
        $haveSth = $this->model->getViewLogByIds($where);
        //有的话,更新,无的话增加
        if(empty($haveSth)) {
            $return = $this -> model -> saveViewLog($data,'');
        }else{
            $return = $this -> model -> saveViewLog($data,$where);
        }

        if($return['status'] == 2){
            returnAjax([], $return['msg'],2);
        }

        $rtd=[
            'id'=>$return['data']['id'],
            'professional'=>$return['data']['professional'],
            'region'=>$return['data']['region']
        ];

        returnAjax($rtd, '增加成功',1);


        returnAjax($rtd, '更新成功',1);
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
