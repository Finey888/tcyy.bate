<?php
namespace app\index\controller;
use think\Request;

class Company extends Common
{
    private $model;
    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->model = new \app\index\model\Company();
    }
    public function index()
    {
        $get=input('get.');
        $data = $this->model->getDataById();
        $this->assign('data',$data);
        return view('Company/index');
    }
    
    /*
    * 保存数据
    */
    public function saveData(){
        $data=[];
        
        if(request()->isPost()){ $data = input('post.'); }else{ return json(['status'=>-1,'msg'=>'未知数据']); }
   
        //修改数据
        $id = $data['id'];
        unset($data['id']);
        $return = $this->model->saveData($data,['id'=>$id]);
        return empty($return)?['status'=>1,'msg'=>'修改成功']:['status'=>-1,'msg'=>$return];
    }
    
    
    /**
     * 上传图片
     * @param $file  文件
     * @return mixed  返回对应错误数据
     */
    public function uploadImg(){
        //文件
        $file = request()->file('logo');
        return uploadImg($file, '/upload/company/logo/');
    }
}
