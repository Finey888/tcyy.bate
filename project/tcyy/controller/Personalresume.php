<?php
namespace app\tcyy\controller;
use think\Request;

class Personalresume extends Common {
	protected $model = null;

    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->model = new \app\tcyy\model\PersonalResume();
    }

    //获取列表
    public function queryPersonalResumeList(){
        $get = input('post.');
        $page = empty($get['page'])?1:$get['page'];
        $limit = empty($get['limit'])?10:$get['limit'];
        $region = empty($get['region'])? '':$get['region'];

        $where=[];
        $where['auditstatus']=['eq',1]; //审核通过=上架
        $where['isdel']=['eq',0];

        if(!empty($region)){
            $where['region'] = ['like', '%'.$region.'%'];
        }

        $data = $this->model->getPageData($page,$limit,$where);

        returnAjax(['data'=>$data,'page'=>$page],'获取数据成功',1);exit();
    }

    //保存会员公司职位信息
    public function savePersonalResume(){
        $get = input('post.');
        if(empty($get['id'])){
            $data['personname'] = empty($get['personname'])? returnAjax([],'缺少个人名称参数',2):$get['personname'];
            $data['birthday'] = empty($get['birthday'])? returnAjax([],'缺少个人生日参数',2):$get['birthday'];
            $data['expectregion'] = empty($get['expectregion'])? returnAjax([],'缺少期望工作区域参数',2):$get['expectregion'];
            $data['telephone'] = empty($get['telephone'])? returnAjax([],'缺少联系电话参数',2):$get['telephone'];
            $data['education'] = empty($get['education'])? returnAjax([],'缺少学历参数',2):$get['education'];
            $data['email'] = empty($get['email'])? returnAjax([],'缺少邮箱地址参数',2):$get['email'];
            $data['jobstatus'] = $get['jobstatus'];
            $data['marriage'] = $get['marriage'];
            $data['wages'] = $get['wages'];
            $data['positiontype'] = $get['positiontype'];
            $data['selfevaluation'] = $get['selfevaluation'];
            $data['jointime'] = $get['jointime'];
            $data['arrivaltime'] = $get['arrivaltime'];
            $data['intentposition'] = $get['intentposition'];
            $data['worknature'] = $get['worknature'];       //工作性质：全职 兼职
            $data['sex'] = $get['sex'];
            $data['ethnic'] = $get['ethnic'];
            $data['workexperience'] = $get['workexperience'];
            $data['address'] = $get['address'];     //地址
            $data['auditstatus'] = 0; //审核状态
            $data['uid'] =  $this->userData->id;
            $return = $this->model->saveData($data);
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
            $return = $this->model->saveData($get,['id'=>$get['id']]);
            if($return['status'] == 2){
                returnAjax([], $return['msg'],2);
            }

            $data = $this->model->getDataById($get['id']);

            $rtd=[
                'id'=>$data['id'],
                'number'=>$data['name'],
                'price'=>$data['region']
            ];

            returnAjax($rtd, '更新成功',1);
        }
    }

    public function getResumeDetail(){
        $get = input('post.');
        if(empty($get['id'])){
            returnAjax([],'无对应简历编号参数',2);
        }
        $data = $this->model->getDataById($get['id']);
        returnAjax($data,"简历详情获取成功",1);
    }

    public function deletePersonalResume(){
        $get = input('post.');
        if(empty($get['id'])){
            returnAjax([],'无对应简历编号参数',2);
        }
        $data = $this->model->delResumeById($get['id']);
        returnAjax([],"删除成功",1);
    }
}
