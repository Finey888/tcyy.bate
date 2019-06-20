<?php
namespace app\tcyy\controller;
use think\Request;

class Personaldeliver extends Base {

	protected $deliverModel = null;

    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this -> deliverModel = new \app\tcyy\model\PersonalDeliver();
    }


    //保存投递职位简历信息
    public function saveResumeDeliverInfo(){
        $get = input('post.');
        $data['rid'] = empty($get['rid'])? returnAjax([],'缺少简历编号参数',2):$get['rid'];
        $data['delivertime'] = strtotime(date('Y-m-d',$get['delivertime']));
        $data['jid'] =  empty($get['jid'])? returnAjax([],'缺少职位编号参数',2):$get['jid'];

        $where = ['rid'=>$get['rid'],'jid'=>$data['jid']];

        //先判断有无投递历史数据
        $haveSth = $this -> deliverModel -> getDeliverData($where);
        //有的话,更新,无的话增加
        if(empty($haveSth)) {
            $return = $this -> deliverModel -> saveDeliverData($data);
        }else{
            $return = $this -> deliverModel -> saveDeliverData($data,$where);
        }

        if($return['status'] == 2){
            returnAjax([], $return['msg'],2);
        }

        $rtd=[
            'id'=>$return['data']['id'],
            'rid'=>$return['data']['rid']
        ];

        returnAjax($rtd, '投递简历成功',1);
    }
}
