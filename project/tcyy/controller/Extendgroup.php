<?php
namespace app\tcyy\controller;
use think\Request;

class Extendgroup extends Base
{
    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
    }
    
    public function getList()
    {
         $ExtendGroup = new \app\tcyy\model\ExtendGroup();
         $data = $ExtendGroup->getList();
         
         $return=[];
         
         foreach($data->toArray() as $k=>$v){
             $return[$k]['id'] = $v['id'];
             $return[$k]['title'] = $v['title'];
             $chidsData = $ExtendGroup->getList($v['id']);
             $return[$k]['childs'] = $chidsData->toArray();
         }
         returnAjax($return,'获取数据成功',1);exit();
    }
    
}
