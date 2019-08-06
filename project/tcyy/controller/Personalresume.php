<?php
namespace app\tcyy\controller;
use think\Request;

class Personalresume extends Common {
	protected $model = null;
	protected $eductionModel = null;
	protected $experienceModel = null;
	protected $qualificationModel = null;
	protected $companyModel = null;
	protected $viewLogModel = null;
	protected $positionLogModel = null;

    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this-> model = new \app\tcyy\model\PersonalResume();
        $this-> eductionModel = new \app\tcyy\model\PersonalEducation();
        $this-> experienceModel = new \app\tcyy\model\PersonalExperience();
        $this-> qualificationModel = new \app\tcyy\model\PersonalQualification();
        $this-> companyModel = new \app\tcyy\model\PersonalCompany();
        $this -> viewLogModel = new \app\tcyy\model\PersonalResumeViewLog();
        $this -> positionLogModel = new \app\tcyy\model\PersonalPosition();
    }

    //获取列表
    public function queryPersonalResumeList(){
        $get = input('post.');
        $page = empty($get['page'])?1:$get['page'];
        $limit = empty($get['limit'])?10:$get['limit'];
        $region = empty($get['region'])? '':$get['region'];
        $education = empty($get['education'])? '':$get['education'];
        $positiontype = empty($get['positiontype'])? '':$get['positiontype'];

        $where=[];
        $where['auditstatus']=['eq',2]; //审核通过=上架
        $where['isdel']=['eq',0];

        if(!empty($region)){
            $where['expectregion'] = ['like', '%'.$region.'%'];
        }
        if(!empty($education)){
            $where['education'] = ['eq', $education];
        }
        if(!empty($positiontype)){
            $where['intentposition'] = ['eq', $positiontype];
        }

        $data = $this->model->getPageData($page,$limit,$where);

        returnAjax(['data'=>$data,'page'=>$page],'获取数据成功',1);exit();
    }

    /**
     * 保存会员公司职位信息
     */
    public function savePersonalResume(){
        $get = input('post.');
        if(empty($get['id'])){
            $data['personname'] = empty($get['personname'])? returnAjax([],'缺少个人名称参数',2):$get['personname'];
            $data['birthday'] = empty($get['birthday'])? returnAjax([],'缺少个人生日参数',2):$get['birthday'];
            $data['expectregion'] = empty($get['expectregion'])? returnAjax([],'缺少期望工作区域参数',2):$get['expectregion'];
            $data['telephone'] = empty($get['telephone'])? returnAjax([],'缺少联系电话参数',2):$get['telephone'];
            $data['education'] = empty($get['education'])? returnAjax([],'缺少学历参数',2):$get['education'];
            $data['email'] = empty($get['email'])? returnAjax([],'缺少邮箱地址参数',2):$get['email'];
            $data['jobstatus'] = empty($get['jobstatus'])?'':$get['jobstatus'];
            $data['marriage'] = empty($get['marriage'])?'':$get['marriage'];
            $data['wages'] = empty($get['wages'])?'':$get['wages'];
//            $data['positiontype'] = empty($get['positiontype'])? returnAjax([],'缺少职位类型参数',2):$get['positiontype'];$get[''];
            $data['selfevaluation'] = empty($get['selfevaluation'])?'':$get['selfevaluation'];
            $data['jointime'] = $get['jointime'];
            $data['arrivaltime'] = empty($get['arrivaltime'])?'':$get['arrivaltime'];
            $data['intentposition'] = $get['intentposition'];
            $data['worknature'] = empty($get['worknature'])?'':$get['worknature'];       //工作性质：全职 兼职
            $data['sex'] = $get['sex'];
            $data['ethnic'] = empty($get['ethnic'])?'':$get['ethnic'];
            $data['workexperience'] = $get['workexperience'];
            $data['address'] = empty($get['address'])?'':$get['address'];     //地址
            $data['auditstatus'] = 1; //审核状态:1-待审核 2-审核通过 3-审核不通过
            $data['uid'] =  $this->userData->id;
            $data['regionid'] = $get['regionid'];
            $return = $this->model->saveData($data);
            if($return['status'] == 2){
                returnAjax([], $return['msg'],2);
            }

            $rtd=[
                'id'=>$return['data']['id'],
                'intentposition'=>$return['data']['intentposition'],
                'expectregion'=>$return['data']['expectregion']
            ];

            returnAjax($rtd, '增加成功',1);
        }else{
            $checkStatu = $this-> model->getDataById($get['id']);
            //如果为审核不通过
            if($checkStatu['auditstatus'] == 3) {
                $get['auditstatus'] = 1;
            }
            $return = $this-> model->saveData($get,['id'=>$get['id']]);
            if($return['status'] == 2){
                returnAjax([], $return['msg'],2);
            }

            $data = $this->model->getDataById($get['id']);

            $rtd=[
                'id'=>$return['data']['id'],
                'intentposition'=>$return['data']['intentposition'],
                'expectregion'=>$return['data']['expectregion']
            ];

            returnAjax($rtd, '更新成功',1);
        }
    }

    /**
     * 根据简历id查看简历详情
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function viewResumeDetail(){
        $get = input('post.');
        $rid = $get['id'];
        if(empty($rid)){
            returnAjax([],'无对应简历信息',2);
        }
        $data = $this->model->getDataById($rid);
        $eduData = $this -> eductionModel -> queryEduList(['rid' => $rid,'status' => 1]);
        $expData = $this -> experienceModel -> queryWorkExperienceList(['rid' => $rid,'status' => 1]);
        $quaData = $this -> qualificationModel -> getQualificationList(['rid' => $rid,'isdel' => 0]);

        $uid = $this -> userData -> id;
        $rt = $this -> model -> getResumeByCondition(['uid'=>$uid,'id' => $rid]);
        //不保存自己浏览自己简历的历史
        if(empty($rt)){
            $where = ['rid'=>$rid,'uid'=>$uid];
            $viewLog['uid'] = $uid;
            $viewLog['rid'] = $rid;
            $viewLog['viewtime'] = strtotime(date('Y-m-d 00:00:00',time()));

            //先判断有无数据
            $haveSth = $this->viewLogModel->getViewLogByIds($where);
            //有的话,更新,无的话增加
            if(empty($haveSth)) {
                $this -> viewLogModel -> saveViewLog($viewLog);
            }else{
                $this -> viewLogModel -> saveViewLog($viewLog,$where);
            }
        }

        $returnData = [
            'id' => $data['id'],
            'personname' => $data['personname'],
            'birthday' => $data['birthday'],
            'expectregion' =>  $data['expectregion'] ,
            'telephone' =>  $data['telephone'] ,
            'education' => $data['education'] ,
            'email' => $data['email'] ,
            'jobstatus' => $data['jobstatus'] ,
            'marriage' => $data['marriage'],
            'wages' => $data['wages'] ,
            'positiontype' => $data['positiontype'] ,
            'selfevaluation' => $data['selfevaluation'] ,
            'jointime' => $data['jointime'],
            'arrivaltime' => $data['arrivaltime'] ,
            'intentposition' => $data['intentposition'] ,
            'worknature' => $data['worknature'] ,       //工作性质：全职 兼职
            'regionid' => $data['regionid'] ,       //工作性质：全职 兼职
            'sex' => $data['sex'],
            'ethnic' => $data['ethnic'],
            'workexperience' => $data['workexperience'],
            'address' => $data['address'],
            'headurl' => $data['headurl'],
            'eduList' => $eduData,
            'expList' => $expData,
            'qualificationList' => $quaData
        ];
        returnAjax($returnData,'获取数据成功',1);exit();
    }

    /**
     * 根据登录用户获取个人简历信息
     */
    public function getPersonalResumeDetail(){
        $uid= $this -> userData -> id;
        $where = ['uid' => $uid];
        $data = $this->model->getResumeByCondition($where);
        if(empty($data)){
            returnAjax([],'无对应简历信息',2);
        }
        returnAjax($data,'获取数据成功',1);exit();
    }

    public function deletePersonalResume(){
        $get = input('post.');
        if(empty($get['id'])){
            returnAjax([],'无对应简历编号参数',2);
        }
        $data = $this->model->delResumeById($get['id']);
        returnAjax([],"删除成功",1);
    }

    //投递简历前验证是否完善简历
    public function deliverResumerCheckBefore(){
        $get = input('post.');

        $uid= $this -> userData -> id;
        $where = ['uid' => $uid];
        $data = $this -> model -> getResumeByCondition($where);
        if(empty($data)){
            returnAjax(1, "无简历信息",1);
        }else if($data['auditstatus'] != '2'){
            returnAjax(2,'简历未审核通过',1);
        }
        returnAjax(3,"有简历信息,可投递",1);
    }

    //获取个人投递职位得列表信息
    public function getCurrentUserDeliverHistory(){
        $get = input('post.');
        $page = empty($get['page'])?1:$get['page'];
        $limit = empty($get['limit'])?10:$get['limit'];

        $uid= $this -> userData -> id;
        $where = ['uid' => $uid];
        $data = $this -> model -> getResumeByCondition($where);
        if(empty($data)){
            returnAjax([], "无简历信息",0);
        }
        $whe = ['dl.rid' => $data['id']];
        $dataArrays = $this -> positionLogModel -> queryCurrentUserDevlierPositions($page,$limit,$whe);

        returnAjax(['data'=>$dataArrays,'page'=>$page],"获取数据成功",1);
    }

    //删除教育经历
    public function delEducation(){
        $get = input('post.');
        $eid= empty($get['id'])? returnAjax([],'缺少教育经历编号参数',2):$get['id'];
        $this -> eductionModel -> deleteById($eid);
        returnAjax([],"删除成功",1);
    }

    //删除工作经历
    public function delWorkExperience(){
        $get = input('post.');
        $weid= empty($get['id'])? returnAjax([],'缺少工作经历编号参数',2):$get['id'];
        $this -> experienceModel -> deleteById($weid);
        returnAjax([],"删除成功",1);
    }

    //删除资格证书信息
    public function delQualification(){
        $get = input('post.');
        $qid= empty($get['id'])? returnAjax([],'缺少资格编号参数',2):$get['id'];
        $this -> qualificationModel -> delById($qid);
        returnAjax([],"删除成功",1);
    }

    public function queryCompanyAndResumeInfo(){
        $uid= $this -> userData -> id;
        $data = $this->model -> queryCompanyAndResumeInfo($uid);
        returnAjax(['data' =>$data],"获取数据成功",1);
    }
}
