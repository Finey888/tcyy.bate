<?php
namespace app\tcyy\controller;
use think\Request;
class Curriculum extends Common
{
    //课堂系统
    private $curriculumModel;
    private $curriculumSubjectsModel;
    private $curriculumCommentModel;
    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->curriculumModel = new \app\tcyy\model\Curriculum();
        $this->curriculumSubjectsModel = new \app\tcyy\model\CurriculumSubjects();
        $this->curriculumCommentModel = new \app\tcyy\model\CurriculumComment();
    }
    
    public function getList(){
        $get = input('post.');
        
        if(empty($get['groupid'])){
            returnAjax([],'缺少参数',2);exit();
        }
        
        $page = empty($get['page'])?1:$get['page'];
        $limit = empty($get['limit'])?10:$get['limit'];
        
        
        $data = $this->curriculumModel->getDataByPage($get['groupid'],$page,$limit);

        $return =[];
        foreach($data as $k=>$v){
            $return[$k]['id'] = $v['id'];
            $return[$k]['group_id'] = $v['group_id'];
            $return[$k]['title'] = $v['title'];
            $return[$k]['creattime'] = $v['creattime'];
            $return[$k]['type'] = $v['type'];
            $return[$k]['price'] = $v['price'];
            $return[$k]['num']= $v['num'];
            $return[$k]['read'] = $v['read'];
            $return[$k]['image'] = $v['image'];
            $return[$k]['user_info']=['uid'=>$v['user_info']['uid'],'nickname'=>$v['user_info']['nickname']];
            //查询权限
            if($v['type'] == 3){
                $cuserMode = new \app\tcyy\model\CurriculumUser();
                $cuData =  $cuserMode->getDataFind($v['id'], $this->userData->id);
                if(!empty($cuData)){
                    $return[$k]['auth'] = 1;
                }else{
                    $return[$k]['auth'] = 2;
                }
            }else if($v['type'] == 2){
               $userMode = new \app\tcyy\model\User();
               $Udata = $userMode->getDataById($this->userData->id);
               if($Udata['vip_end_date'] >= strtotime(date('Y-m-d',time()))){
                   $return[$k]['auth'] = 1;
               }else{
                   $return[$k]['auth'] = 2;
               }
            }else{
                //是否购买
                $CurriculumUserMode = new \app\tcyy\model\CurriculumUser();
                $CurriculumUser = $CurriculumUserMode->getDataFind($this->userData->id,$v['id']);
                if(empty($CurriculumUser)){
                    $return[$k]['auth'] = 2;
                }else{
                    $return[$k]['auth'] = 1;
                }
            }
        }
        
        returnAjax(['data'=>$return,'page'=>$page],'获取数据成功',1);exit();
        
    }
    
    /**
     * @获取详情
     */
    public function details(){
        $get = input('post.');
        
        if(empty($get['id'])){
            returnAjax([],'缺少参数',2);exit();
        }
        
        $data = $this->curriculumModel->getDataById($get['id']);
        $return=[];
        $return['id'] = $data['id'];
        $return['group_id'] = $data['group_id'];
        $return['title'] = $data['title'];
        $return['creattime'] = $data['creattime'];
        $return['type'] = $data['type'];
        $return['price'] = $data['price'];
        $return['num']= $data['num'];
        $return['read'] = $data['read'];
        $return['image'] = $data['image'];
        
        $return['contents'] = html_entity_decode(html_entity_decode($data['contents'],ENT_QUOTES ),ENT_QUOTES );
        $hostStr = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'];
        
        $return['contents']=preg_replace('/(<[img|IMG].*?src=[\'|\"])(\/)(.*?(?:[\.jpg|\.jpeg|\.png|\.gif|\.bmp]))[\'|\"].*?[\/]?/','${1}'.$hostStr.'${2}${3}"',$return['contents']);
      
        $return['user_info']=['uid'=>$data['user_info']['uid'],'nickname'=>$data['user_info']['nickname']];
        if($this->userData->id == $data['user_info']['uid']){
            $return['auth'] = 1;
        }else{
            $return['auth'] = 2;
        }
        returnAjax($return,'获取数据成功',1);exit();
    }
    
    /**
     * @获取课程内容列表
     */
    public function getSubjectsList(){
        $get = input('post.');
        
        if(empty($get['groupid'])){
            returnAjax([],'缺少参数',2);exit();
        }
        
        $page = empty($get['page'])?1:$get['page'];
        $limit = empty($get['limit'])?10:$get['limit'];
        $data = $this->curriculumSubjectsModel->getDataByPage($get['groupid'],$page,$limit);
        
        returnAjax(['data'=>$data,'page'=>$page],'获取数据成功',1);exit();
    }
    
    /**
     * @获取评论列表
     */
    public function getCommentsList(){
        $get = input('post.');
        
        if(empty($get['cid'])){
            returnAjax([],'缺少参数',2);exit();
        }
        
        $page = empty($get['page'])?1:$get['page'];
        $limit = empty($get['limit'])?10:$get['limit'];
        $pid = empty($get['pid'])?0:$get['pid'];
        $data=[];
        $data = $this->curriculumCommentModel->getDataByPage($get['cid'],$pid,$page,$limit);
        $return = [];
        foreach($data as $k=>$v){
            $return[$k]=$v;
            $return[$k]['child_comments'] = $this->curriculumCommentModel->getDataByPage($get['cid'],$v['id'],1,3);
            $return[$k]['child_comment_count'] = $this->curriculumCommentModel->getChildCommentCount($get['cid'],$v['id'],$v['id']);
        }
        
        returnAjax(['data'=>$return,'page'=>$page],'获取评论数据成功！',1);exit();
    }
    
    /**
     * @评论
     */
    public function comments(){
        $get = input('post.');
        
        if(empty($get['cid'])){
            returnAjax([],'缺少参数',2);exit();
        }
        
        if(empty($get['pid']) && $get['pid']!=0){
            returnAjax([],'缺少参数',2);exit();
        }
        
        if(empty($get['contents'])){
            returnAjax([],'评论内容不能为空！',2);exit();
        }
        
        $data = $this->curriculumCommentModel->addData($this->userData->id,$get['cid'],$get['pid'],$get['contents']);
        if(!$data){
            returnAjax([],'评论失败',2);exit();
        }else{
            returnAjax([],'评论成功',1);exit();
        }
    }
    
    //保存数据
    public function saveSubjects(){
        $get = input('post.');
        if(empty($get['title'])){
            returnAjax([],'缺少参数',2);exit();
        }
        if(empty($get['contents'])){
            returnAjax([],'缺少参数',2);exit();
        }
        if(empty($get['cid'])){
            returnAjax([],'缺少参数',2);exit();
        }
        
        $data = $this->curriculumSubjectsModel->saveData(['title'=>$get['title'],'contents'=>htmlspecialchars($get['contents'],ENT_QUOTES),'cid'=>$get['cid'],'times'=>time()]);
        
        if(!$data){
            returnAjax([],'保存失败！',2);exit();
        }
        
        returnAjax([],'保存成功',1);exit();
    }
    
    //获取详情
    public function getSubjectsDetails(){
        $get = input('post.');
        if(empty($get['id'])){
            returnAjax([],'缺少参数',2);exit();
        }
        
        $data = $this->curriculumSubjectsModel->getDataById($get['id']);
      
        if(!empty($data['contents'])){
            $data['contents'] = html_entity_decode(html_entity_decode($data['contents'],ENT_QUOTES ),ENT_QUOTES );
            $hostStr = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'];
            $data['contents']=preg_replace('/(<[img|IMG].*?src=[\'|\"])(\/)(.*?(?:[\.jpg|\.jpeg|\.png|\.gif|\.bmp]))[\'|\"].*?[\/]?/','${1}'.$hostStr.'${2}${3}"',$data['contents']);
        }
        
        returnAjax($data,'获取数据成功',1);exit();
        
    }
    
    //删除课堂安排
    public function delSubjects(){
        $get = input('post.');
        if(empty($get['id'])){
            returnAjax([],'缺少参数',2);exit();
        }
        
        $data = $this->curriculumSubjectsModel->editStatus($get['id']);
        
         returnAjax('','删除成功',1);exit();
    }
    
}
