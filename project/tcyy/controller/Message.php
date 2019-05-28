<?php
namespace app\tcyy\controller;
use think\Request;
class Message extends Common
{
    private $messageModel;
    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->messageModel = new \app\tcyy\model\Message();
    }
    
    /**
     * @保存消息
     */
    public function saveMessage(){
        
        $data = $this->messageModel->addData($this->userData->id,'',$page,$limit);
       
        returnAjax(['data'=>$data,'page'=>$page],'获取成功',1);exit();          
        
    }
    
    /**
     * @获取未读条数
     */
    public function getUnread(){
        $count = $this->messageModel->getUnreadCount($this->userData->id);
        returnAjax(['count'=>$count],'获取成功',1);exit(); 
    }
    
    /**
     * @获取消息列表
     */
    public function getList(){
        $get = input('post.');
        $page = empty($get['page'])?1:$get['page'];
        $limit = empty($get['limit'])?10:$get['limit'];
        $data = $this->messageModel->getDataList($this->userData->id,$page,$limit);
        returnAjax(['data'=>$data,'page'=>$page],'获取成功',1);exit(); 
    }
    
    /**
     * @标记为已读
     */
    public function signRead(){
        $get = input('post.');
        
        if(empty($get['id'])){
            returnAjax([],'缺少参数',2);exit();
        }
        
        $this->messageModel->saveRead($get['id'],$this->userData->id);
        
        returnAjax([],'标记已读',1);exit(); 
    }
    
    /**
     * @消息详情
     */
    public function getDetails(){
        $get = input('post.');
        
        if(empty($get['id'])){
            returnAjax([],'缺少参数',2);exit();
        }
        
        $data = $this->messageModel->findData($get['id']);
        
        returnAjax($data,'获取成功',1);exit(); 
    }
    
}
