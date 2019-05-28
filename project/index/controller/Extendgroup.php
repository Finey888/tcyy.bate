<?php
namespace app\index\controller;
use think\Request;

class Extendgroup extends Common
{
    private $model;
    private $extendDisplay;
    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->model = new \app\index\model\ExtendGroup();
        $this->extendDisplay = new \app\index\model\ExtendDisplay();
    }
    public function index()
    {
        $pData = $this->model->getAllData(0);
        $this->assign('pdata',$pData);
        
        $typedata = $this->extendDisplay->getAllData();
        $this->assign('typedata',$typedata);
        
        return view('Extendgroup/index');
    }
    
    //分组
    public function add(){
        
        $displaydata = $this->extendDisplay->getAllData();
        $this->assign('displaydata',$displaydata);
        
        $pData = $this->model->getAllData(0);
        $this->assign('data',$pData);
        
        return view('Extendgroup/add');
    }
    
    //修改菜单
    public function edit(){
        $id = input('get.id');
        
        $displaydata = $this->extendDisplay->getAllData();
        $this->assign('displaydata',$displaydata);
        
        $pData = $this->model->getAllData(0);
        $this->assign('pdata',$pData);
        
        $data = $this->model->getDataById($id);
        $this->assign('data',$data);

        return view('Extendgroup/edit');
    }
    
    //获取列表
    public function getList(){
        $get = input('get.');
        if(!empty($get['status'])&&$get['status']!='0'){
            $where['status'] = $get['status'];
        }
        $where['pid'] = $get['pid'];
        if(!empty($get['keyword'])){
            $where['title'] = ['like', '%'.$get['keyword'].'%'];
        }
        $where['status']=['neq',-1];
        
        if(!empty($get['type'])&&$get['type']!='0'){
            $where['type'] = $get['type'];
        }
        
        $data = $this->model->getPageData($where);
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
   
   
   /**
     * 上传图片
     * @param $file  文件
     * @return mixed  返回对应错误数据
     */
    public function uploadImg(){
        //文件
        $file = request()->file('icon');
        return uploadImg($file, '/upload/extendgroup/images/');
    }
}
