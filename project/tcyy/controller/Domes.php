<?php
namespace app\tcyy\controller;
use think\Request;
class Domes extends Base
{
    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
    }
    
    /**
     * @获取分类
     */
    public function getGroup(){
         $domeGroup = new \app\tcyy\model\DomeGroup();
         $data = $domeGroup->getList();
         foreach($data as $k=>$v){
             $imgData = $v['dome_image']->toArray();
             $data[$k]['dome_image']=$imgData;
         }
         returnAjax($data->toArray(),'获取数据成功',1);exit();
    }
   
    /**
     * @获取底图
     */
    public function getBgList(){
        $domeBg = new \app\tcyy\model\DomeBg();
        $data = $domeBg->getList();
        returnAjax($data->toArray(),'获取数据成功',1);exit();
    }
}
