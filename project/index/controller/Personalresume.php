<?php
namespace app\index\controller;

use think\Request;

class Personalresume extends Common {
	protected $model = null;
    protected $eductionModel = null;
    protected $experienceModel = null;
    protected $qualificationModel = null;

    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this -> model = new \app\index\model\PersonalResume;
        $this-> eductionModel = new \app\index\model\PersonalEducation();
        $this-> experienceModel = new \app\index\model\PersonalExperience();
        $this-> qualificationModel = new \app\index\model\PersonalQualification();
    }

    //默认入口
    public function index(){
        $get = input('get.');
        $where = ['isdel' => 0];

        if(!empty($get['personname'])){
            $where['personname'] = ['like', '%'.$get['personname'].'%'];
        }
        if(!empty($get['telephone'])){
            $where['telephone'] = ['like', '%'.$get['telephone'].'%'];
        }
        if(!empty($get['email'])){
            $where['email'] = ['like', '%'.$get['email'].'%'];
        }
        if(!empty($get['auditstatus'])){
            $where['auditstatus'] = ['eq',$get['auditstatus']];
        }

        $countPage = $this -> model -> getCount($where);
        $this -> assign('countPage',$countPage);
        return view('Personalresume/index');
    }

    //获取列表
    public function getList(){
        $get = input('get.');
        $page = empty($get['page'])?1:$get['page'];
        $count = empty($get['count'])?10:$get['count'];

        $where = ['isdel' => 0];

        if(!empty($get['personname'])){
            $where['personname'] = ['like', '%'.$get['personname'].'%'];
        }
        if(!empty($get['telephone'])){
            $where['telephone'] = ['like', '%'.$get['telephone'].'%'];
        }
        if(!empty($get['email'])){
            $where['email'] = ['like', '%'.$get['email'].'%'];
        }
        if(!empty($get['auditstatus'])){
            $where['auditstatus'] = ['eq',$get['auditstatus']];
        }

        $data = $this -> model -> getPageData($page,$count,$where);

        return json(['data'=>$data,'status'=>1,'msg'=>'获取数据成功']);
    }

    public function auditData(){
        if(request()->isPost()){ $id = input('post.id'); }else{ return json(['status'=>-1,'msg'=>'未知数据']); }

        $return = $this->model->auditDataById($id);

        return $return ? ['status'=>1,'msg'=>'审核通过'] : ['status'=>-1,'msg'=>'审核失败'];
    }

    //查询详情
    public function viewDetails(){
        if(request()->isGet()){
            $get = input('get.');
        }else{
            return json(['status'=>-1,'msg'=>'未知数据']);
        }

        if(empty($get['id'])){
            return json(['status'=>-1,'msg'=>'未知数据']);
        }
        $rid = $get['id'];
        $data = $this-> model -> getDataById($rid);
        $eduData = $this -> eductionModel -> queryEduList(['rid' => $rid]);
        $expData = $this -> experienceModel -> queryWorkExperienceList(['rid' => $rid]);
        $quaData = $this -> qualificationModel -> getQualificationList(['rid' => $rid]);

        $this->assign('data',$data);
        $this->assign('eduData',$eduData);
        $this->assign('expData',$expData);
        $this->assign('quaData',$quaData);
        return view('Personalresume/details');
    }
}
