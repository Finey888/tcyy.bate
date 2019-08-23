<?php
namespace app\index\controller;
use think\Request;

class Cases extends Common {

    private $caseModel;

    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this-> caseModel = new \app\index\model\Cases();
        $this-> groupModel = new \app\index\model\Group();
    }

    //默认入口
    public function index(){
        $get = input('get.');

        $group = $this->groupModel->getAllData();
        $this->assign('group', $group);

        $where ='';

        if(isset($get['group_id']) && $get['group_id'] != 0){
            $where = ['group_id'=>$get['group_id']];
        }
        $countPage = $this->caseModel->getCount($where);
        $this->assign('countPage',$countPage);
        return view('Cases/index');
    }

    //获取详情
    public function details(){
        $get = input('get.');

        $data = $this->caseModel->getCasesDetails($get['id']);
        $return['contents'] = empty($data['contents'])?"":$data['contents'];
        $return['user_info'] = $data['user_info'];
        $return['thing'] = $data['thing'];
        $return['read'] = $data['read'];
        $return['comments'] = $data['comments'];
        $return['creatime']= $data['creatime'];
        if(empty($data['cases_image'])){
            $return['cases_image']=[];
        }else{
            $return['cases_image']=$data['cases_image']->toArray();
        };
        $this->assign('data',$return);
        return view('Cases/details');
    }

    public function getList(){
        $get = input('get.');
        $where = ['status'=>1];
        if(isset($get['group_id']) && $get['group_id'] != 0){
            $where = ['group_id'=>$get['group_id']];
        }
        $page = empty($get['page'])?1:$get['page'];
        $limit = empty($get['limit'])?10:$get['limit'];
        $data = $this->caseModel->getDataByPage($where,$page,$limit);

        return json(['data'=>$data,'status'=>1,'msg'=>'获取数据成功']);

    }

}


