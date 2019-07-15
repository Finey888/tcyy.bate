<?php
namespace app\tcyy\controller;
use think\Request;

class Personalposition extends Common {
	protected $model = null;
	protected $companyModel = null;

    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->model = new \app\tcyy\model\PersonalPosition();
        $this->companyModel = new \app\tcyy\model\PersonalCompany();
    }

    //获取列表
    public function queryPositionByCondition(){
        $get = input('post.');
        $page = empty($get['page'])?1:$get['page'];
        $limit = empty($get['limit'])?10:$get['limit'];
        $professional = empty($get['professional'])? '':$get['professional'];
        $begintime = empty($get['begintime'])? '':$get['begintime'];
        $endtime = empty($get['endtime'])? '':$get['endtime'];
        $region = empty($get['region'])? '':$get['region'];
        $postiontype = empty($get['positiontype'])? '':$get['positiontype'];    //职位类型
        $experience = empty($get['experience'])? '':$get['experience'];         //工作经验
        $education = empty($get['education'])? '':$get['education'];            //学历
        $nature = empty($get['nature'])? '':$get['nature'];                     //1-全职 2-兼职

        $where=[];
        $where['pt.status']=['eq',1];
        $where['pt.isdel']=['eq',0];

        if(!empty($professional)){
            $where['pt.professional'] = ['like', '%'.$professional.'%'];
        }
        if(!empty($region)){
            $where['pt.region'] = ['like', '%'.$region.'%'];
        }
        if(!empty($postiontype)){
            $where['pt.positiontype'] = ['eq', $postiontype];
        }
        if(!empty($experience)){
            $where['pt.experience'] = ['eq', $experience];
        }
        if(!empty($education)){
            $where['pt.education'] = ['eq', $education];
        }
        if(!empty($nature)){
            $where['pt.nature'] = ['eq', $nature];
        }
        if(!empty($begintime) && !empty($endtime)) {
            $beginDate = strtotime($begintime);
            $endtime = strtotime($endtime);
            $where[] = ['exp', 'pt.lasttime >= ' . $beginDate . ' and pt.lasttime <= ' . $endtime];
        }
        $data = $this->model->getPageData($page,$limit,$where);

        returnAjax(['data'=>$data,'page'=>$page],'获取数据成功',1);exit();
    }

    //保存会员公司职位信息
    public function saveCompanyPositionByVIP(){
        $get = input('post.');
        if(empty($get['id'])){
            $data['positiontype'] = empty($get['positiontype'])? returnAjax([],'缺少职位类型参数',2):$get['positiontype'];
            $data['professional'] = empty($get['professional'])? returnAjax([],'缺少招聘知名名称参数',2):$get['professional'];
            $data['region'] = empty($get['region'])? returnAjax([],'缺少区域参数',2):$get['region'];
            $data['descriptions'] = empty($get['descriptions']) ? returnAjax([],'缺少职位描述参数',2):$get['descriptions'];
            $data['nums'] = empty($get['nums'])? returnAjax([],'缺少招聘人数参数',2):$get['nums'];
            $data['education'] = empty($get['education'])? returnAjax([],'缺少学历参数',2):$get['education'];
            $data['nature'] = $get['nature'];       //职位属性：全职 兼职
            $data['address'] = $get['address'];     //地址
            $data['experience'] = $get['experience']; //工作经验
            $data['lasttime'] = strtotime(date('Y-m-d',time()));
            $data['creatime'] = strtotime(date('Y-m-d ',time()));

//            $data['cid'] = empty($get['cid'])? returnAjax([],'缺少公司编号参数',2):$get['cid'];
            $uid =  $this->userData->id;
            $comp = $this -> companyModel -> getCompanyByCondition(['uid' => $uid]);
            $data['cid'] = $comp['id'];
            if(empty($data['cid'])){
                returnAjax([], '没有对应的公司信息',2);
            }

            $data['regionid'] = empty($get['regionid']);
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
                'professional'=>$data['professional'],
                'region'=>$data['region']
            ];

            returnAjax($rtd, '更新成功',1);
        }
    }

    public function getPositionDetail(){
        $get = input('post.');
        if(empty($get['id'])){
            returnAjax([],'无对应职位编号参数',2);
        }
        $data = $this->model->getDataById($get['id']);
        returnAjax($data,"获取详情成功",1);
    }

    public function deletePosition(){
        $get = input('post.');
        if(empty($get['id'])){
            returnAjax([],'无对应职位编号参数',2);
        }
        $data = $this->model->delPostionById($get['id']);
        returnAjax([],'操作成功',1);
    }
}
