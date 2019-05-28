<?php
namespace app\index\controller;
use think\Request;

class Pictureclass extends Common
{
    private $model;
    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->model = new \app\index\model\Group();
    }
    public function index()
    {
        $get = input('get.');
        
        $where=[];
        
        $where['status']=['neq',-1];
        $where['type']=1;
        
        if(!empty($get['pid'])&&$get['pid']!='0'){
            $where['pid'] = $get['pid'];
        }
        
        $count = $this->model->getCount($where);
        
        $this->assign('count',$count);
        
        //顶级分类
        $group = $this->model->getAllData();
        $this->assign('group',$group);
        
        return view('Pictureclass/index');
    }
    
    //添加权限
    public function add(){
        //顶级分类
        $group = $this->model->getAllData();
        $this->assign('group',$group);
        
        return view('Pictureclass/add');
    }
    
    //修改菜单
    public function edit(){
        $id = input('get.id');
        $data = $this->model->getDataById($id);//一级菜单
        $this->assign('data',$data);
        //顶级分类
        $group = $this->model->getAllData();
        $this->assign('group',$group);
        
        return view('Pictureclass/edit');
    }
    
    //获取列表
    public function getList(){
        $get = input('post.');
        $pid='';
        if(!empty($get['pid'])&&$get['pid']!='0'){
            $pid = $get['pid'];
        }
        
        //顶级分类
        $group = $this->model->getAllData();
        $this->assign('group',$group);
        
        
        $data = $this->model->getListByPid($pid);
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
       $data['type']=1;
       if(empty($data['id'])){
           //添加数据
           $return = $this->model->saveData($data);
           return ($return==1)?['status'=>1,'msg'=>'添加成功']:['status'=>-1,'msg'=>$return];
       }else{
           //修改数据
           $id = $data['id'];
           $return = $this->model->saveData($data,['id'=>$id]);
           return ($return==1)?['status'=>1,'msg'=>'修改成功']:['status'=>-1,'msg'=>$return];
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
