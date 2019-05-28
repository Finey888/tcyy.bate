<?php
namespace app\index\controller;
use think\Request;

class Vipprice extends Common
{
    private $model;
    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->model = new \app\index\model\Vip();
    }
    public function index()
    {
        return view('Vipprice/index');
    }
    
    public function add()
    {
        return view('Vipprice/add');
    }
    
    public function edit()
    {
        $id = input('get.id');
        $data = $this->model->getDataById($id);
        $this->assign('data',$data);
        return view('Vipprice/edit');
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
           if(empty($return)){
               return ['status'=>1,'msg'=>'添加成功'];
           }else{
               return ['status'=>-1,'msg'=>$return];
           }
       }else{
           //修改数据
           $id = $data['id'];
           unset($data['id']);
           $return = $this->model->saveData($data,['id'=>$id]);
           return empty($return)?['status'=>1,'msg'=>'修改成功']:['status'=>-1,'msg'=>$return];
       }
    }
   
    //获取列表
    public function getList(){
        $data = $this->model->getPageData();
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
        $return = $this->model->setStatus($get['id'],$get['status']);
        return $return==1?['status'=>1,'msg'=>'修改成功']:['status'=>-1,'msg'=>'修改失败'];
   }
   
   /**
     * 修改
     * @param int $id 数据ID
     * @return array 返回操作状态
     */
   public function del(){
        if(request()->isPost()){ $get = input('post.'); }else{ return json(['status'=>-1,'msg'=>'未知数据']); }
        if(empty($get['id'])){ return json(['status'=>-1,'msg'=>'未知数据']); }
        $return = $this->model->setStatus($get['id'],-1);
        return $return==1?['status'=>1,'msg'=>'删除成功']:['status'=>-1,'msg'=>'删除失败'];
   }
   
}
