<?php
namespace app\tcyy\controller;
use think\Request;
class Brand extends Base
{
    private $brandModel;
    private $brandClassModel;
    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->brandModel = new \app\tcyy\model\Brand();
        $this->brandClassModel = new \app\tcyy\model\BrandClass();
    }
    
    /**
     * @获取品牌分类
     * @param int $type 类型
     */
    public function getGroup(){
        $get = input('post.');
        if(empty($get['type'])){
            returnAjax([],'缺少参数',2);exit();
        }
        
        $data = $this->brandClassModel->getDataAll($get['type']);
        $result = $data->toArray();
        
        returnAjax($result,'获取数据成功',1);exit();
    }
    /**
     * @品牌列表
     * @param int $group_id 分类ID
     */
    public function getList(){
        $get = input('post.');
        
        if(empty($get['type'])){
            returnAjax([],'缺少参数',2);exit();
        }
        
        $page = empty($get['page'])?1:$get['page'];
        $limit = empty($get['limit'])?20:$get['limit'];
        
        $where['status']=1;
        $where[]=['exp','find_in_set("'.$get['type'].'",type)'];
        
        if(!empty($get['classid'])){
            $where[]=['exp','find_in_set("'.$get['classid'].'",class)'];
        }
        
        if(!empty($get['keyword'])){
            $where['title']=['like','%'.$get['keyword'].'%'];
        }
        
        $data = $this->brandModel->getDataByPage($where,$page,$limit);
 
        returnAjax(['data'=>$data,'page'=>$page],'获取数据成功',1);exit();
    }
}
