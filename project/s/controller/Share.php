<?php
namespace app\s\controller;
use think\Request;
class Share extends Base
{
    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
    }
    
    /**
     * @分享邀请码
     * @uid 用户ID
     */
    public function invitation(){
        $get = input('get.');
        $user = new \app\s\model\User();
        $userData = $user->getDataById($get['uid']);
        $this->assign('userinfo',$userData);
        return view('invitation');
    }
    
    /**
     * @知识库
     * @id  数据ID
     */
    public function knowledge(){
        $get = input('get.');
        $BrandProduct = new \app\s\model\Knowledge();
        $data = $BrandProduct->getDataById($get['id']);
        $data['contents'] = html_entity_decode(html_entity_decode($data['contents']));
  
        $this->assign('data',$data);
        return view('knowledge');
    }
    
    /**
     * @焦点关注
     * @ID
     */
    public function dts(){
        $get = input('get.');
        $BrandProduct = new \app\s\model\BrandProduct();
        $data = $BrandProduct->getDataById($get['id']);
        $data['contents'] = html_entity_decode(html_entity_decode($data['contents']));
  
        $this->assign('data',$data);
        return view('ShareFocusDetails');
    }
    
    /**
     * @病例秀
     * @ID
     */
    public function cases(){
        $get = input('get.');
        $BrandProduct = new \app\s\model\Cases();
        $data = $BrandProduct->getDataById($get['id']);

        $this->assign('data',$data);
        $this->assign('images',empty($data['cases_image'])?[]:$data['cases_image']->toArray());
        return view('cases');
    }
    
    /**
     * @项目
     * @ID
     */
    public function project(){
        
        $get = input('get.');
        $projectUserModel = new \app\s\model\ProjectUser();
        $ProjectDetailsModel = new \app\s\model\ProjectDetails();
        
        $data = $projectUserModel->getDataDetails($get['id']);
     
        $dataDetails = $ProjectDetailsModel->getDataByIds($data['pids']);

        $project = [];
        foreach($dataDetails as $k=>$v){
            $pd=[];
            $pd['price'] = $v['price'];
            $pd['num'] = $v['num'];
            $pd['title'] = $v['project']['title'];
            $pd['number'] = $v['project']['number'];
            $pd['image'] = $v['project']['image'];
            $project[] = $pd;
        }
        
        $return=[
            'id'=>$data['id'],
            'name'=>$data['name'],
            'sex'=>$data['sex'],
            'age'=>$data['age'],
            'phone'=>$data['phone'],
            'booktime'=>$data['booktime'],
            'totalprice'=>$data['price'],
            'status'=>$data['type'],
            'tid'=>$data['project_timing_one']['id'],
            'project'=>$project,
            'zzys'=>$data['zzys'],
            'jzs'=>$data['jzs'],
            'image'=>$data['image']
        ];
        $this->assign('data',$return);
        return view('project');
      
    }
    
}
