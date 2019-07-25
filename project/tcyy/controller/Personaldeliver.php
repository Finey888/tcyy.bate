<?php
namespace app\tcyy\controller;
use think\Request;

class Personaldeliver extends Common {

	protected $deliverModel = null;
	protected $resumeModel = null;
	protected $positionModel = null;

    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this -> deliverModel = new \app\tcyy\model\PersonalDeliver();
        $this -> resumeModel = new \app\tcyy\model\PersonalResume();
        $this -> positionModel = new \app\tcyy\model\PersonalPosition();
    }


    //保存投递职位简历信息
    public function saveResumeDeliverInfo(){
        $get = input('post.');

        $data['delivertime'] = strtotime(date('Y-m-d 00:00:00',time()));
        $data['jid'] =  empty($get['jid'])? returnAjax([],'缺少职位编号参数',2):$get['jid'];

        //根据登录用户获取自己的简历编号
        $uid= $this -> userData -> id;
        $rsWhere = ['uid' => $uid];
        $rtnResume = $this->resumeModel->getResumeByCondition($rsWhere);
        if(empty($rtnResume['id'])){
            returnAjax([], '没有对应简历信息',2);
        }
        $data['rid'] = $rtnResume['id'];

        //根据登录用户编号和职位查询是否投递自己发布的职位
        $positionCountWhere = ['pt.id' => $get['jid'],'cm.uid' => $uid];
        $count = $this -> positionModel -> getPositionCountByUid($positionCountWhere);
        if($count > 0){
            returnAjax([], '不能投递自己发布的职位',2);
        }

        $where = ['rid'=>$data['rid'],'jid'=>$data['jid']];
        //先判断有无投递历史数据
        $haveSth = $this -> deliverModel -> getDeliverData($where);
        //有的话,更新,无的话增加
        if(empty($haveSth)) {
            $return = $this -> deliverModel -> saveDeliverData($data);
        }else{
            $where = ['id'=>$data['id']];
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
