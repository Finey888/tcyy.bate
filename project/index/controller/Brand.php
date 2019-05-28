<?php
namespace app\index\controller;
use think\Request;

//品牌管理
class Brand extends Common
{
    private $model;
    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->model = new \app\index\model\Brand();
    }
    public function index()
    {
        $get = input('get.');
        $where['status']=['neq',-1];
        
        if(!empty($get['class'])){
            $classStr='';
            foreach($get['class'] as $class){
                $classStr.='find_in_set('.$class.',class) or ';
            }
            $where[] = ['exp',substr($classStr,0,strlen($classStr)-3)];
        }
    
        if(!empty($get['type'])){
            $typeStr='';
            foreach($get['type'] as $type){
               $typeStr.='find_in_set('.$type.',type) or ';
            }
            $where[] = ['exp',substr($typeStr,0,strlen($typeStr)-3)];
        }
     
        $data = $this->model->getListData($where);//获取数据
        $this->assign('data',$data);
      
        $ExtendGroupModel = new \app\index\model\ExtendGroup();
        $ExtendGroup = $ExtendGroupModel->getDataByType(3);//所有的分类
        $this->assign('ExtendGroup',$ExtendGroup);
        
        $classModel = new \app\index\model\BrandClass();
        $class = $classModel->getAllData();//所有的分类
        $this->assign('class',$class);
        
        return view('Brand/index');
    }
    
    //添加
    public function add(){
        $classModel = new \app\index\model\BrandClass();
        $class = $classModel->getAllData();//所有的分类
        $this->assign('class',$class);
        
        $ExtendGroupModel = new \app\index\model\ExtendGroup();
        $ExtendGroup = $ExtendGroupModel->getDataByType(3);//所有的分类
        $this->assign('ExtendGroup',$ExtendGroup);
        
        return view('Brand/add');
    }
    
    //修改
    public function edit(){
        $id = input('get.id');
        
        $classModel = new \app\index\model\BrandClass();
        $class = $classModel->getAllData();//所有的分类
        $this->assign('class',$class);
        
        $ExtendGroupModel = new \app\index\model\ExtendGroup();
        $ExtendGroup = $ExtendGroupModel->getDataByType(3);//所有的分类
        $this->assign('ExtendGroup',$ExtendGroup);
        
        $data = $this->model->getDataById($id);
        $this->assign('data',$data);
        return view('Brand/edit');
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
        $file = request()->file('image');
        return uploadImg($file, '/upload/brand/images/');
    }
}
