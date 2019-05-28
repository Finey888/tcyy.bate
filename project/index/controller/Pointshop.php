<?php
namespace app\index\controller;
use think\Request;

class Pointshop extends Common
{
    private $shopModel;

    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->shopModel = new \app\index\model\PointShop();
    }
    public function index()
    {
        $get = input('get.');
        $where['status']=['neq',-1];
        
        if(!empty($get['status'])&&$get['status']!='0'){
            $where['status'] = $get['status'];
        }
        
        if(!empty($get['type'])&&$get['type']!='0'){
            if($get['type'] == 1){
                $where['endtime'] = ['>=', strtotime(date('Y-m-d 00:00:00',time()))];
            }elseif($get['type'] == 2){
                $where['endtime'] = ['<', strtotime(date('Y-m-d 23:59:59',time()))];
            }
        }
        
        
        if(!empty($get['keyword'])){
            $where['title'] = ['like', '%'.$get['keyword'].'%'];
        }
        
        $countPage = $this->shopModel->getCount($where);
        
        $this->assign('countPage',$countPage);
        return view('Pointshop/index');
    }
    
    //
    public function add(){
        return view('Pointshop/add');
    }
    
    //
    public function edit(){
        $id = input('get.id');
        
        $data = $this->shopModel->getDataById($id);
        $this->assign('data',$data);

        return view('Pointshop/edit');
    }
    
    //获取列表
    public function getList(){
        $get = input('get.');
        $where['status']=['neq',-1];
        
        if(!empty($get['status'])&&$get['status']!='0'){
            $where['status'] = $get['status'];
        }
        
        if(!empty($get['type'])&&$get['type']!='0'){
            if($get['type'] == 1){
                $where['endtime'] = ['>=', strtotime(date('Y-m-d 00:00:00',time()))];
            }elseif($get['type'] == 2){
                $where['endtime'] = ['<', strtotime(date('Y-m-d 23:59:59',time()))];
            }
        }
        
        if(!empty($get['keyword'])){
            $where['title'] = ['like', '%'.$get['keyword'].'%'];
        }
   
        $data = $this->shopModel->getPageData($get['page'],$get['count'],$where);
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
        $return = $this->shopModel->setStatus($get['id'],$get['status']);
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
           $return = $this->shopModel->saveData($data);
           return $return?['status'=>1,'msg'=>'添加成功']:['status'=>-1,'msg'=>'添加失败！'];
       }else{
           //修改数据
           $id = $data['id'];
           unset($data['id']);
           $return = $this->shopModel->saveData($data,['id'=>$id]);
           return $return?['status'=>1,'msg'=>'修改成功']:['status'=>-1,'msg'=>'修改失败！'];
       }
   }
   
   /*
    * 删除数据
    */
   public function del(){
       if(request()->isPost()){ $id = input('post.id'); }else{ return json(['status'=>-1,'msg'=>'未知数据']); }
       
       $return = $this->shopModel->delById($id);
       
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
        return uploadImg($file, '/upload/pointshop/images/');
    }
    
}
