<?php
namespace app\index\controller;
use think\Request;

class Advert extends Common
{
    private $model;
    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->model = new \app\index\model\Advert();
    }
    
    public function index()
    {
        $data = $this->model->getPageData();
        $this->assign('data',$data);
        return view('Advert/index');
    }
    
    //添加广告
    public function add(){
        return view('Advert/add');
    }
    
    //修改广告
    public function edit(){
        $id = input('get.id');
        $data = $this->model->getDataById($id);
        $this->assign('data',$data);
        return view('Advert/edit');
    }
    
    /**
     * 上传图片
     * @param $file  文件
     * @return mixed  返回对应错误数据
     */
    public function uploadImg(){
        //文件
        $file = request()->file('image');
        return uploadImg($file, '/upload/advert/images/');
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
            $adt = new \app\index\model\AdvertTime();
            $adt->saveData(['times'=>time(),'aid'=>$this->model->id]);
            
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
            $adt = new \app\index\model\AdvertTime();
            $adt->saveData(['times'=>time(),'aid'=>$id]);
            return empty($return)?['status'=>1,'msg'=>'修改成功']:['status'=>-1,'msg'=>$return];
        }
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
    * 删除数据
    */
   public function del(){
       if(request()->isPost()){ $id = input('post.id'); }else{ return json(['status'=>-1,'msg'=>'未知数据']); }
       
       $return = $this->model->delById($id);
       $adt = new \app\index\model\AdvertTime();
            $adt->saveData(['times'=>time(),'aid'=>$id]);
       return $return?['status'=>1,'msg'=>'删除成功']:['status'=>-1,'msg'=>'删除失败'];
   }
   
   public function setColor(){
        $adt = new \app\index\model\AdvertColor();
        $data = $adt->getDataById();
        $this->assign('data',$data);
        return view('Advert/color');
   }
   
   public function saveColor(){
       $data=[];
        $adt = new \app\index\model\AdvertColor();
        if(request()->isPost()){ $data = input('post.'); }else{ return json(['status'=>-1,'msg'=>'未知数据']); }
        //修改数据
        $id = $data['id'];
        unset($data['id']);
        $return = $adt->saveData($data,['id'=>$id]);
        
        $adt = new \app\index\model\AdvertTime();
            $adt->saveData(['times'=>time(),'aid'=>0]);
        
        return empty($return)?['status'=>1,'msg'=>'修改成功']:['status'=>-1,'msg'=>$return];
   }
}
