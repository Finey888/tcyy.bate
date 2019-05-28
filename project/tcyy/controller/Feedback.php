<?php
namespace app\tcyy\controller;
use think\Request;
class Feedback extends Common
{
    private $feedModel;
    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->feedModel = new \app\tcyy\model\Sugest();
    }
    
    public function saveData(){
        $get = input('post.');
        
        if(empty($get['contents'])){
            returnAjax([],'缺少参数',2);exit();
        }
        
        $data = $this->feedModel->addData(['contents'=>$get['contents'],'uid'=>$this->userData->id,'time1'=>time()]);
        
        if(!$data){
            returnAjax([],'提交失败！',2);exit();
        }
        returnAjax([],'提交成功！',1);exit();
    }
}
