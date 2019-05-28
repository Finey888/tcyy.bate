<?php
namespace app\tcyy\controller;
use think\Request;
class Brandslide extends Base
{
    private $brandslideModel;
    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->brandslideModel = new \app\tcyy\model\BrandSlide();
    }
    
    /**
     * @获取幻灯片
     * @param int $group_id 分类ID
     */
    public function getList(){
        $get = input('post.');
        
        if(empty($get['type'])){
            returnAjax([],'缺少参数',2);exit();
        }
        
        $data = $this->brandslideModel->getDataByType($get['type']);
 
        returnAjax($data->toArray(),'获取数据成功',1);exit();
    }
}
