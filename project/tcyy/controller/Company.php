<?php
namespace app\tcyy\controller;
use think\Request;
class Company extends Common
{
    private $companyModel;
    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->companyModel = new \app\tcyy\model\Company();
    }
    
    public function getCompanyData(){
        $data = $this->companyModel->getDataFind();
        
        if(!$data){
            returnAjax([],'获取失败！',2);exit();
        }
        returnAjax($data,'获取成功！',1);exit();
    }
}
