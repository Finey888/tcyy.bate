<?php
namespace app\tcyy\controller;
use think\Request;
class Brandproduct extends Base
{
    private $brandProductClassModel;
    private $brandProductModel;
    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->brandProductClassModel = new \app\tcyy\model\BrandProductClass();
        $this->brandProductModel = new \app\tcyy\model\BrandProduct();
    }
    
    /**
     * @获取产品分类
     * @param int $brand_id 类型
     */
    public function getGroup(){
        $get = input('post.');
        if(empty($get['brand_id'])){
            returnAjax([],'缺少参数',2);exit();
        }
        
        $data = $this->brandProductClassModel->getDataAll($get['brand_id']);
        $result = $data->toArray();
        
        returnAjax($result,'获取数据成功',1);exit();
    }
    /**
     * @品牌列表
     * @param int $group_id 分类ID
     */
    public function getList(){
        $get = input('post.');
        
        if(empty($get['brand_id'])){
            returnAjax([],'缺少参数',2);exit();
        }
        
        if(empty($get['type'])){
            returnAjax([],'缺少参数',2);exit();
        }
        
        $page = empty($get['page'])?1:$get['page'];
        $limit = empty($get['limit'])?20:$get['limit'];
        
        $where['status']=1;
        $where['brand_id']=$get['brand_id'];
        $where['type']=$get['type'];
        
        if(!empty($get['product_class'])){
            $where['product_class']=$get['product_class'];
        }
        
        if(!empty($get['keyword'])){
            $where['title']=['like','%'.$get['keyword'].'%'];
        }
        
        $data = $this->brandProductModel->getDataByPage($where,$page,$limit);
 
        returnAjax(['data'=>$data,'page'=>$page],'获取数据成功',1);exit();
    }
    
    
    public function getDetails(){
        $get = input('post.');
        
        if(empty($get['id'])){
            returnAjax([],'缺少参数',2);exit();
        }
        
        $data = $this->brandProductModel->getDataById($get['id']);
        $result = $data->toArray();
     
        $result['contents'] = html_entity_decode(html_entity_decode($result['contents'],ENT_QUOTES ),ENT_QUOTES );
        
        $hostStr = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'];
        $result['contents']=preg_replace('/(<[img|IMG].*?src=[\'|\"])(\/)(.*?(?:[\.jpg|\.jpeg|\.png|\.gif|\.bmp]))[\'|\"].*?[\/]?/','${1}'.$hostStr.'${2}${3}"',$result['contents']);
 
        $this->brandProductModel->saveRead($get['id']);
        returnAjax($result,'获取数据成功',1);exit();
    }
    
}

