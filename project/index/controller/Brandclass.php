<?php
namespace app\index\controller;
use think\Request;

class Brandclass extends Common
{
    private $model;
    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->model = new \app\index\model\BrandClass();
    }
    public function index()
    {
        return view('Brandclass/index');
    }
    
    //添加权限
    public function add(){
        $ExtendGroupModel = new \app\index\model\ExtendGroup();
        $ExtendGroup = $ExtendGroupModel->getDataByType(3);//所有的分类
        $this->assign('ExtendGroup',$ExtendGroup);
        return view('Brandclass/add');
    }
    
    //修改菜单
    public function edit(){
        $id = input('get.id');
        $data = $this->model->getDataById($id);//一级菜单
        
        $ExtendGroupModel = new \app\index\model\ExtendGroup();
        $ExtendGroup = $ExtendGroupModel->getDataByType(3);//所有的分类
        $this->assign('ExtendGroup',$ExtendGroup);
    
        $this->assign('data',$data);
        return view('Brandclass/edit');
    }
    
    //获取列表
    public function getList(){
        $data = $this->model->getPageData();//一级菜单
        
        return ['data'=>$data,'status'=>1,'msg'=>'数据获取成功'];
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
        $return = $this->model->setStatus($get['id'],$get['status']);
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
           $return = $this->model->saveData($data);
           return empty($return)?['status'=>1,'msg'=>'添加成功']:['status'=>-1,'msg'=>$return];
       }else{
           //修改数据
           $id = $data['id'];
           $return = $this->model->saveData($data,['id'=>$id]);
           return empty($return)?['status'=>1,'msg'=>'修改成功']:['status'=>-1,'msg'=>$return];
       }
   }
   
   /*
    * 删除数据
    */
   public function del(){
       if(request()->isPost()){ $id = input('post.id'); }else{ return json(['status'=>-1,'msg'=>'未知数据']); }
       
       $return = $this->model->delById($id);
       
       return $return?['status'=>1,'msg'=>'删除成功']:['status'=>-1,'msg'=>'删除失败'];
   }
}
