<?php
namespace app\tcyy\controller;
use think\Request;
class Knowledge extends Base
{
    private $knowledgeModel;
    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->knowledgeModel = new \app\tcyy\model\Knowledge();
    }
    
    /**
     * @获取知识库
     * @param int $group_id 分类ID
     */
    public function getList(){
        $get = input('post.');
        
        if(empty($get['groupid'])){
            returnAjax([],'缺少参数',2);exit();
        }
        
        $page = empty($get['page'])?1:$get['page'];
        $limit = empty($get['limit'])?10:$get['limit'];
        $data = $this->knowledgeModel->getDataByPage($get['groupid'],$page,$limit);
        $result = $data->toArray();
        
        returnAjax(['data'=>$result,'page'=>$page],'获取数据成功',1);exit();
    }
    
    /**
     * @获取详情
     */
    public function getDetails(){
        $get = input('post.');
        
        if(empty($get['id'])){
            returnAjax([],'缺少参数',2);exit();
        }
        
        $data = $this->knowledgeModel->getDataById($get['id']);
        $result = empty($data)?[]:$data->toArray();
        if(!empty($data)){
            $result['contents'] = html_entity_decode(html_entity_decode($result['contents'],ENT_QUOTES ),ENT_QUOTES );
            $hostStr = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'];
            $result['contents']=preg_replace('/(<[img|IMG].*?src=[\'|\"])(\/)(.*?(?:[\.jpg|\.jpeg|\.png|\.gif|\.bmp]))[\'|\"].*?[\/]?/','${1}'.$hostStr.'${2}${3}"',$result['contents']);

            $this->knowledgeModel->saveRead($get['id']);
        }
        returnAjax($result,'获取数据成功',1);exit();
    }
}
