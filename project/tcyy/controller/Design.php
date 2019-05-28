<?php
namespace app\tcyy\controller;
use think\Request;
class Design extends Base
{
    private $designModel;
    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->designModel = new \app\tcyy\model\Design();
    }
    
    /**
     * @获取基础分类
     */
    public function getList()
    {
        $get = input('post.');
        if(empty($get['group_id'])){
            returnAjax([],'缺少参数',2);exit();
        }
        
        $page = empty($get['page'])?1:$get['page'];
        $limit = empty($get['limit'])?10:$get['limit'];
        
        $data = $this->designModel->getDataByPage($get['group_id'],$page,$limit);
        returnAjax(['data'=>$data->toArray(),'page'=>$page],'获取数据成功',1);exit();
    }
    
}
