<?php
namespace app\tcyy\controller;
use think\Request;
class Video extends Base
{
    private $videoModel;
    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->videoModel = new \app\tcyy\model\Video();
    }
    
    /**
     * @获取视频列表
     * @param int $group_id 分类ID
     */
    public function getList(){
        $get = input('post.');
        
        if(empty($get['groupid'])){
            returnAjax([],'缺少参数',2);exit();
        }
        
        $page = empty($get['page'])?1:$get['page'];
        $limit = empty($get['limit'])?10:$get['limit'];
        $data = $this->videoModel->getDataByPage($get['groupid'],$page,$limit);
        $result = $data->toArray();
        returnAjax(['data'=>$result,'page'=>$page],'获取数据成功',1);exit();
    }
}
