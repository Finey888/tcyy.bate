<?php
namespace app\index\controller;
use think\Request;

class Brandproduct extends Common
{
    private $model;
    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->model = new \app\index\model\BrandProduct();
    }
    public function index()
    {
        $brand_id=input('get.brand_id');
        $branmode = new \app\index\model\Brand();
        $brand_data = $branmode->getDataById($brand_id);
        $this->assign('brand',$brand_data);
    
        $pc = new \app\index\model\BrandProductClass();
        $pdata = $pc->getDataByBrandId($brand_id);
        $this->assign('pdata',$pdata);
     
        $ids =implode(',', $brand_data['type']);
        
        $ExtendGroupModel = new \app\index\model\ExtendGroup();
        $ExtendGroup = $ExtendGroupModel->getDataByIds($ids);//所有的分类
        $this->assign('ExtendGroup',$ExtendGroup);
        
        
        $get = input('get.');
        $where['brand_id'] = $get['brand_id'];
        $where['status']=['neq',-1];
        if(!empty($get['keyword'])){
            $where['title'] = ['like', '%'.$get['keyword'].'%'];
        }
        
        if(!empty($get['status'])&&$get['status']!='0'){
            $where['status'] = $get['status'];
        }
        
        if(!empty($get['product_class'])&&$get['product_class']!='0'){
            $where['product_class'] = $get['product_class'];
        }
        
        if(!empty($get['type'])&&$get['type']!='0'){
            $where['type'] = $get['type'];
        }
        
        $count = $this->model->getCount($where);
        $this->assign('countPage',$count);
        return view('Brandproduct/index');
    }
    
    public function add(){
        $brand_id = input('get.brand_id');
       
        $this->assign('brand_id',$brand_id);
        //查询分类
        $pc = new \app\index\model\BrandProductClass();
        $data = $pc->getDataByBrandId($brand_id);
        $this->assign('data',$data);
       
        $ExtendGroupModel = new \app\index\model\ExtendGroup();
        $ExtendGroup = $ExtendGroupModel->getDataByType(3);//所有的分类
        $this->assign('ExtendGroup',$ExtendGroup);
        
        return view('Brandproduct/add');
    }
    
    public function edit(){
        $id = input('get.id');
        
        $data = $this->model->getDataById($id);//一级菜单
        $this->assign('data',$data);
     
        $pc = new \app\index\model\BrandProductClass();
        $pdata = $pc->getDataByBrandId($data['brand_id']);
        $this->assign('pdata',$pdata);
     
        $ExtendGroupModel = new \app\index\model\ExtendGroup();
        $ExtendGroup = $ExtendGroupModel->getDataByType(3);//所有的分类
        $this->assign('ExtendGroup',$ExtendGroup);
   
        
        return view('Brandproduct/edit');
    }
    
    //获取列表
    public function getList(){
        $get = input('get.');
        $where['brand_id'] = $get['brand_id'];
        $where['status']=['neq',-1];
        if(!empty($get['keyword'])){
            $where['title'] = ['like', '%'.$get['keyword'].'%'];
        }
        
        if(!empty($get['status'])&&$get['status']!='0'){
            $where['status'] = $get['status'];
        }
        
        if(!empty($get['product_class'])&&$get['product_class']!='0'){
            $where['product_class'] = $get['product_class'];
        }
        
        if(!empty($get['type'])&&$get['type']!='0'){
            $where['type'] = $get['type'];
        }
        
        $data = $this->model->getPageData($get['page'],$get['count'],$where);//一级菜单
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
   
   /**
     * 上传图片
     * @param $file  文件
     * @return mixed  返回对应错误数据
     */
    public function uploadImg(){
        //文件
        $file = request()->file('image');
        return uploadImg($file, '/upload/brandproduct/images/');
    }
}
