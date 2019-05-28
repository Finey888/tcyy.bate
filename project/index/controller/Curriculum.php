<?php
namespace app\index\controller;
use think\Request;

class Curriculum extends Common
{
    private $groupModel;
    private $userModel;
    private $curriculumModel;
    private $curriculumSubjects;

    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->groupModel = new \app\index\model\Group();
        $this->userModel = new \app\index\model\User();
        $this->curriculumModel = new \app\index\model\Curriculum();
        $this->curriculumSubjects = new \app\index\model\CurriculumSubjects();
    }
    public function index()
    {
        $group = $this->groupModel->getAllData();
        $this->assign('group',$group);
       
        $get = input('get.');
        $where['status']=['neq',-1];
        
        if(!empty($get['status'])&&$get['status']!='0'){
            $where['status'] = $get['status'];
        }
        
        if(!empty($get['keyword'])){
            $where['title'] = ['like', '%'.$get['keyword'].'%'];
        }
        
        //1.免费   2.限会员  3.付款买断
        if(!empty($get['type'])&&$get['type']!='0'){
            $where['type'] = $get['type'];
        }
        
        //1.免费   2.限会员  3.付款买断
        if(!empty($get['group_id'])&&$get['group_id']!='0'){
            $where['group_id'] = $get['group_id'];
        }
        
        $countPage = $this->curriculumModel->getCount($where);
        
        $this->assign('countPage',$countPage);
        return view('Curriculum/index');
    }
    
    //
    public function add(){
        $group = $this->groupModel->getAllData();
        $this->assign('group',$group);
        $userData = $this->userModel->getPageData(1,1000,['status'=>1,'type'=>2]);
        $this->assign('userdata',$userData);
        return view('Curriculum/add');
    }
    
    //
    public function edit(){
        $id = input('get.id');
        
        $group = $this->groupModel->getAllData();
        $this->assign('group',$group);
        
        $userData = $this->userModel->getPageData(1,1000,['status'=>1,'type'=>2]);
        $this->assign('userdata',$userData);
        
        $data = $this->curriculumModel->getDataById($id);
        $this->assign('data',$data);

        return view('Curriculum/edit');
    }
    
    //获取列表
    public function getList(){
        $get = input('get.');
        
        $where['status']=['neq',-1];
        
        if(!empty($get['status'])&&$get['status']!='0'){
            $where['status'] = $get['status'];
        }
        
        if(!empty($get['keyword'])){
            $where['title'] = ['like', '%'.$get['keyword'].'%'];
        }
        
        //1.免费   2.限会员  3.付款买断
        if(!empty($get['type'])&&$get['type']!='0'){
            $where['type'] = $get['type'];
        }
        
        //1.免费   2.限会员  3.付款买断
        if(!empty($get['group_id'])&&$get['group_id']!='0'){
            $where['group_id'] = $get['group_id'];
        }
   
        $data = $this->curriculumModel->getPageData($get['page'],$get['count'],$where);
        return json(['data'=>$data,'status'=>1,'msg'=>'获取数据成功']);
    }
    
    /**
     * 修改状态
     * @param int $id 数据ID
     * @param int $status 状态
     * @return array 返回操作状态
     */
   public function editStatus(){
        $id='';
        if(request()->isGet()){ $get = input('get.'); }else{ return json(['status'=>-1,'msg'=>'未知数据']); }
        if(empty($get['id'])){ return json(['status'=>-1,'msg'=>'未知数据']); }
        if(empty($get['status'])){ return json(['status'=>-1,'msg'=>'未知数据']); }
        $return = $this->curriculumModel->setStatus($get['id'],$get['status']);
        return $return==1?['status'=>1,'msg'=>'修改成功']:['status'=>-1,'msg'=>'修改失败'];
   }
   
   /*
    * 保存数据
    */
   public function saveData(){
       $data=[];
       if(request()->isPost()){ $data = input('post.'); }else{ return json(['status'=>-1,'msg'=>'未知数据']); }
       
       if(empty($data['id'])){
           //添加数据
           $return = $this->curriculumModel->saveData($data);
           return empty($return)?['status'=>1,'msg'=>'添加成功']:['status'=>-1,'msg'=>$return];
       }else{
           //修改数据
           $id = $data['id'];
           unset($data['id']);
           $return = $this->curriculumModel->saveData($data,['id'=>$id]);
           return empty($return)?['status'=>1,'msg'=>'修改成功']:['status'=>-1,'msg'=>$return];
       }
   }
   
   /*
    * 删除数据
    */
   public function del(){
       if(request()->isPost()){ $id = input('post.id'); }else{ return json(['status'=>-1,'msg'=>'未知数据']); }
       
       $return = $this->curriculumModel->delById($id);
       
       return $return?['status'=>1,'msg'=>'删除成功']:['status'=>-1,'msg'=>'删除失败'];
   }
   
   
   /**
     * 上传图片
     * @param $file  文件
     * @return mixed  返回对应错误数据
     */
    public function uploadImg(){
        //文件
        $file = request()->file('image');
        return uploadImg($file, '/upload/extendgroup/images/');
    }
    
    /**
     * @编辑的课程
     */
    public function subjects(){
        $get=input('get.');
        
        $this->assign('cid',$get['cid']);
        
        $where['status']=['neq',-1];
        $where['cid']=$get['cid'];
        if(!empty($get['status'])&&$get['status']!='0'){
            $where['status'] = $get['status'];
        }
        
        if(!empty($get['keyword'])){
            $where['title'] = ['like', '%'.$get['keyword'].'%'];
        }
        
        $countPage = $this->curriculumSubjects->getCount($where);
        $this->assign('countPage',$countPage);
        
        return view('Curriculum/subjects'); 
    }
    
    /**
     * @获取科目列表
     */
    public function getSubjectsList(){
        $get = input('get.');
        
        $where['status']=['neq',-1];
        $where['cid']=$get['cid'];
        if(!empty($get['status'])&&$get['status']!='0'){
            $where['status'] = $get['status'];
        }
        
        if(!empty($get['keyword'])){
            $where['title'] = ['like', '%'.$get['keyword'].'%'];
        }
   
        $data = $this->curriculumSubjects->getPageData($get['page'],$get['count'],$where);
        return json(['data'=>$data,'status'=>1,'msg'=>'获取数据成功']);
        
    }
    
    /**
     * @查看详情
     */
    public function subjectsDts(){
        $id = input('get.id');
        
        $data = $this->curriculumSubjects->getDataById($id);
        $this->assign('data',$data);

        return view('Curriculum/subjectsDts');
    }
    
    public function subjectsStatus(){
        $id='';
        if(request()->isGet()){ $get = input('get.'); }else{ return json(['status'=>-1,'msg'=>'未知数据']); }
        if(empty($get['id'])){ return json(['status'=>-1,'msg'=>'未知数据']); }
        if(empty($get['status'])){ return json(['status'=>-1,'msg'=>'未知数据']); }
        $return = $this->curriculumSubjects->setStatus($get['id'],$get['status']);
        return $return==1?['status'=>1,'msg'=>'修改成功']:['status'=>-1,'msg'=>'修改失败'];
   }
     
}
