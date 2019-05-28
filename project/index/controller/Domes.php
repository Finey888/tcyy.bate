<?php
namespace app\index\controller;
use think\Request;

//演示库
class Domes extends Common
{
    private $model;
    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->model = new \app\index\model\DomeImage();
    }
    
    public function index()
    {
        $get = input('get.');
        
        $where=[];
        
        $where['status']=['neq',-1];
        
        if(!empty($get['status'])&&$get['status']!='0'){
            $where['status'] = $get['status'];
        }
        
        if(!empty($get['group_id'])&&$get['group_id']!='0'){
            $where['group_id'] = $get['group_id'];
        }
        
        //顶级分类
        $groupModel = new \app\index\model\DomeGroup();
        $group = $groupModel->getPageData();
        $this->assign('group',$group);
        
        $data = $this->model->getPageData($where);
        $this->assign('data',$data);
       
        
        return view('Domes/index');
    }
    
    
    //添加广告
    public function add(){
        //顶级分类
        $groupModel = new \app\index\model\DomeGroup();
        $group = $groupModel->getPageData();
        $this->assign('group',$group);
        
        return view('Domes/add');
    }
    
    //修改广告
    public function edit(){
        $id = input('get.id');
        $data = $this->model->getDataById($id);
        $this->assign('data',$data);
        
        //顶级分类
        $groupModel = new \app\index\model\DomeGroup();
        $group = $groupModel->getPageData();
        $this->assign('group',$group);
        
        return view('Domes/edit');
    }
    
    /**
     * 上传图片
     * @param $file  文件
     * @return mixed  返回对应错误数据
     */
    public function uploadImg(){
        //文件
        $file = request()->file('image');
        return uploadImg($file, '/upload/Domes/images/');
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
    
   /*
    * 删除数据
    */
   public function del(){
       if(request()->isPost()){ $id = input('post.id'); }else{ return json(['status'=>-1,'msg'=>'未知数据']); }
       
       $return = $this->model->delById($id);
       
       return $return?['status'=>1,'msg'=>'删除成功']:['status'=>-1,'msg'=>'删除失败'];
   }
}
