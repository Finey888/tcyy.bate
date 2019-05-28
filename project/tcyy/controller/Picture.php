<?php
namespace app\tcyy\controller;
use think\Request;
class Picture extends Base
{
    private $pictureModel;
    private $folderModel;
    private $userPictureModel;
    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->pictureModel = new \app\tcyy\model\Picture();
        $this->folderModel = new \app\tcyy\model\UserFolder();
        $this->userPictureModel = new \app\tcyy\model\UserPicture();
    }
    
    /**
     * @获取图片列表
     * @param int $group_id 分类ID
     */
    public function getList(){
        $get = input('post.');
        
        if(empty($get['folderid'])){
            returnAjax([],'缺少参数',2);exit();
        }
        $page = empty($get['page'])?1:$get['page'];
        $limit = empty($get['limit'])?10:$get['limit'];
        if(empty($get['type']) || $get['type'] ==1){
            $data = $this->pictureModel->getDataByPage($get['folderid'],$page,$limit);//系统图片
        }else{
            $com = new \app\tcyy\controller\Common();
            $data = $this->userPictureModel->getDataByPage($get['folderid'],$page,$limit);//用户图片
        }
        returnAjax(['data'=>$data,'page'=>$page],'获取数据成功',1);exit();
    }
    
    
    /**
     * @新增用户文件夹
     */
    public function addFolder(){
        $get = input('post.');
        $com = new \app\tcyy\controller\Common();
        $user = $com->getUserData();
 
        if(empty($get['groupid'])){
            returnAjax([],'缺少参数',2);exit();
        }
        
        if(empty($get['title'])){
            returnAjax([],'缺少参数',2);exit();
        }
        
        $data = $this->folderModel->addData($get, $user->id);
        
        if($data){
            returnAjax(['id'=>$data,'title'=>$get['title']],'创建成功',1);exit();
        }else{
            returnAjax([],'创建失败',2);exit();
        }
    }
    
    public function delFolder(){
        $get = input('post.');
        $com = new \app\tcyy\controller\Common();
        $user = $com->getUserData();
 
        if(empty($get['id'])){
            returnAjax([],'缺少参数',2);exit();
        }
        
        $data = $this->folderModel->del($get['id'], $user->id);
     
        if($data){
            returnAjax([],'删除成功',1);exit();
        }else{
            returnAjax([],'删除失败',2);exit();
        }
    }
    
    /**
     * @上传图片
     */
    public function addImg(){
        $get = input('post.');
        $com = new \app\tcyy\controller\Common();
        $user = $com->getUserData();
        
        if(empty($get['fid'])){
            returnAjax([],'缺少参数',2);exit();
        }
        
        if(empty($get['name'])){
            returnAjax([],'缺少参数',2);exit();
        }
        
        if(empty($get['image'])){
            returnAjax([],'缺少参数',2);exit();
        }
        
        $data = $this->userPictureModel->addData($get, $user->id);
        
        if($data){
            returnAjax(['id'=>$data,'title'=>$get['name'],'image'=>$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$get['image']],'上传成功',1);exit();
        }else{
            returnAjax([],'上传失败',2);exit();
        }
    }
    
    
     public function delImg(){
        $get = input('post.');
        $com = new \app\tcyy\controller\Common();
        $user = $com->getUserData();
 
        if(empty($get['id'])){
            returnAjax([],'缺少参数',2);exit();
        }
        
        $data = $this->userPictureModel->del($get['id'], $user->id);
     
        if($data){
            returnAjax([],'删除成功',1);exit();
        }else{
            returnAjax([],'删除失败',2);exit();
        }
    }
}
