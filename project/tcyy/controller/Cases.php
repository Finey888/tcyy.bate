<?php
namespace app\tcyy\controller;
use think\Request;
class Cases extends Common
{
    private $caseModel;
    private $caseFollwModel;
    private $caseThingModel;
    private $caseCommentModel;
    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->caseModel = new \app\tcyy\model\Cases();
        $this->caseFollwModel = new \app\tcyy\model\CasesFollow();
        $this->caseThingModel = new \app\tcyy\model\CasesThing();
        $this->caseCommentModel = new \app\tcyy\model\CasesComment();
    }
    
    //获取详情
    public function getDetails(){
        $get = input('post.');
        
        if(empty($get['id'])){
            returnAjax([],'缺少参数',2);exit();
        }
        
        
        
        $this->caseModel = new \app\tcyy\model\Cases();
        
        $data = $this->caseModel->getDataByIdss($get['id'],$this->userData->id);
   
        if(empty($data)){
            returnAjax([],'数据已被删除',1);exit();
        }
        
        $return =[];
        
            if($data['uid'] == $this->userData->id){
                $return['isMe']=1;
            }else{
                $return['isMe']=2;
            }
                
            $return['id'] = $data['id'];
            $return['contents'] = $data['contents'];
            $return['thing'] = $data['thing'];
            $return['read'] = $data['read'];
            $return['comments'] = $data['comments'];
            $return['group_id'] = $data['group_id'];
            $return['creatime']= $data['creatime'];
            $return['userinfo'] = $data['user_info'];
            //是否关注  1关注  2非关注
            if(!empty($data['cases_follow']) && $data['cases_follow']['status'] == 1){
                    $return['isfollow'] = 1;
            }else{
                    $return['isfollow'] = 2;
            }
            if(empty($data['cases_image'])){$return['cases_image']=[];}else{$return['cases_image']=$data['cases_image']->toArray();};
            
            $caseModel = new \app\tcyy\model\Cases();
            $caseModel->saveRead($get['id']);
            
        returnAjax($return,'获取数据成功',1);exit();
    }
    
    public function getList(){
        $get = input('post.');
        
        if(empty($get['groupid'])){
           // returnAjax([],'缺少参数',2);exit();
        }
        
        $page = empty($get['page'])?1:$get['page'];
        $limit = empty($get['limit'])?10:$get['limit'];
        $type = empty($get['type'])?1:$get['type'];
     
        $group = '';
        if($type == 1){
            if(empty($get['groupid'])){
             returnAjax([],'缺少参数',2);exit();
            }
            $group=$get['groupid'];
        }
        $this->caseModel = new \app\tcyy\model\Cases();
        $data = $this->caseModel->getDataByPage($this->userData->id,$group,$type,$page,$limit);
		
        $return =[];
        foreach($data as $k=>$v){
            if($v['uid'] == $this->userData->id){
                $return[$k]['isMe']=1;
            }else{
                $return[$k]['isMe']=2;
            }
                
            $return[$k]['id'] = $v['id'];
            $return[$k]['contents'] = $v['contents'];
            $return[$k]['thing'] = $v['thing'];
            $return[$k]['read'] = $v['read'];
            $return[$k]['comments'] = $v['comments'];
            $return[$k]['group_id'] = $v['group_id'];
            $return[$k]['creatime']= $v['creatime'];
            $return[$k]['userinfo'] = $v['user_info'];
            $return[$k]['type'] = $v['type'];
            //是否关注  1关注  2非关注
            if(!empty($v['cases_follow']) && $v['cases_follow']['status'] == 1){
                    $return[$k]['isfollow'] = 1;
            }else{
                    $return[$k]['isfollow'] = 2;
            }
            if(empty($v['cases_image'])){$return[$k]['cases_image']=[];}else{$return[$k]['cases_image']=$v['cases_image']->toArray();};
        }
        
        returnAjax(['data'=>$return,'page'=>$page],'获取数据成功',1);exit();
        
    }
    
    /**
     * @关注、取消关注
     */
    public function follow(){
        $get = input('post.');
        //查询是否已经关注
        if(empty($get['touid'])){
            returnAjax([],'缺少参数',2);exit();
        }
        
        if($this->userData->id == $get['touid']){
            returnAjax([],'不能关注自己！',2);exit();
        }
        $this->caseFollwModel = new \app\tcyy\model\CasesFollow();
        $data = $this->caseFollwModel->getDataByTouid($this->userData->id, $get['touid']);
        if(empty($data)){
            //添加关注
            $return = $this->caseFollwModel->addFollowData($this->userData->id, $get['touid']);
            if(!$return){
                returnAjax(['status'=>1],'关注失败',2);exit();
            }else{
                //关注推送
                $miCon = new \app\tcyy\controller\Mipush();
                $payload=['type'=>1];
                $miCon->send(['title'=>'有人关注了你！','desc'=>$this->userData->auth_group->nickname.' 关注了你！','payload'=>$payload], $get['touid']);
                
                //插入消息库
                $mesModel = new \app\tcyy\model\Message();
                $mesModel->addData($this->userData->auth_group->nickname.' 关注了你', 5, $get['touid'],'','','');
        
                returnAjax(['status'=>1],'关注成功',1);exit();
            }
        }elseif($data['status'] == 1){
            //已经关注，取消关注
            $return = $this->caseFollwModel->saveData($this->userData->id, $get['touid'],-1);
            if(!$return){
                returnAjax(['status'=>2],'取消关注失败',2);exit();
            }else{
                returnAjax(['status'=>2],'取消关注成功',1);exit();
            }
        }else{
            //修改为取消关注
            $return = $this->caseFollwModel->saveData($this->userData->id, $get['touid'],1);
            if(!$return){
                returnAjax(['status'=>1],'关注失败',2);exit();
            }else{
                //关注推送
                $miCon = new \app\tcyy\controller\Mipush();
                $payload=['type'=>1];
                $miCon->send(['title'=>'有人关注了你！','desc'=>$this->userData->auth_group->nickname.' 关注了你！','payload'=>$payload], $get['touid']);
                
                //插入消息库
                $mesModel = new \app\tcyy\model\Message();
                $mesModel->addData($this->userData->auth_group->nickname.' 关注了你', 5, $get['touid'],'','','');
                
                returnAjax(['status'=>1],'关注成功',1);exit();
            }
        }
    }
    
    /**
     * @点赞
     */
    public function thing(){
        $get = input('post.');
        
        if(empty($get['case_id'])){
            returnAjax([],'缺少参数',2);exit();
        }
        $find = $this->caseThingModel->getDataByUidAndCaseId($this->userData->id, $get['case_id']);
        if(!empty($find)){
            returnAjax([],'已点赞',2);exit();
        }
        
        $data = $this->caseThingModel->addThingData($this->userData->id, $get['case_id']);
        
        if(!$data){
            returnAjax([],'点赞失败',2);exit();
        }
		
        $this->caseModel->saveThing($get['case_id']);
        
        //消息
        $caseData = $this->caseModel->getDataById($get['case_id']);
        $mesModel = new \app\tcyy\model\Message();
        $mesModel->addData($this->userData->auth_group->nickname.' 赞了你', 4, $caseData['uid'],'','',$get['case_id']);
        
        //关注推送
        $caseData = $this->caseModel->getDataById($get['case_id']);
        $miCon = new \app\tcyy\controller\Mipush();
        $payload=['type'=>6];
        $miCon->send(['title'=>'有人赞了你！','desc'=>$this->userData->auth_group->nickname.' 赞了你！','payload'=>$payload], $caseData['uid']);
        
        returnAjax([],'点赞成功',1);exit();
    }
	
    public function read(){
        $get = input('post.');
        
        if(empty($get['case_id'])){
            returnAjax([],'缺少参数',2);exit();
        }

        $this->caseModel->saveRead($get['case_id']);

        returnAjax([],'浏览量+1',1);exit();
    }
    
    /**
     * @评论
     */
    public function comments(){
        $get = input('post.');
        
        if(empty($get['case_id'])){
            returnAjax([],'缺少参数',2);exit();
        }
        
        if(empty($get['pid']) && $get['pid']!=0){
            returnAjax([],'缺少参数',2);exit();
        }
        
        if(empty($get['contents'])){
            returnAjax([],'评论内容不能为空！',2);exit();
        }
        
        $touid=0;
        $sfzpl = 2;
        if(!empty($get['touid'])){
            $touid = $get['touid'];
            $sfzpl = 6;
        }
        
        $data = $this->caseCommentModel->addData($this->userData->id,$get['case_id'],$get['pid'],$get['contents'],$touid);
        
        if(!$data){
            returnAjax([],'评论失败',2);exit();
        }else{
            $toUserData=[];
            if(!empty($get['touid'])){
                $userModel = new \app\tcyy\model\UserInfo();
                $toUser = $userModel->getDataByUid($touid);
                $toUserData=[
                    'id'=>$toUser['id'],
                    'uid'=>$toUser['uid'],
                    'nickname'=>$toUser['nickname'],
                    'headurl'=>$toUser['headurl']
                ];
            }
            
            $return=[
                'id'=>$data,
                'uid'=>$this->userData->id,
                'case_id'=>$get['case_id'],
                'pid'=>$get['pid'],
                'contents'=>$get['contents'],
                'times'=>date('Y-m-d H:i:s',time()),
                "user_info"=>[
                            "id"=>$this->userData->id,
                            "uid"=> $this->userData->id,
                            "nickname"=>$this->userData->auth_group['nickname'],
                            "headurl"=> $this->userData->auth_group['headurl']
                        ],
                'to_user_info'=>$toUserData
            ];
            
            $caseData = $this->caseModel->getDataById($get['case_id']);
            
            if($get['pid'] == '0'){            
                $this->caseModel->saveComment($get['case_id']);
            }
            
            
            //消息
            $mesModel = new \app\tcyy\model\Message();
            $mesModel->addData($this->userData->auth_group->nickname.' 评论了你', $sfzpl, $caseData['uid'],'',$get['contents'],$get['case_id']);
            
            //推送
            $miCon = new \app\tcyy\controller\Mipush();
            $payload=['type'=>2];
            $miCon->send(['title'=>'有人评论了你！','desc'=>$this->userData->auth_group->nickname.' 评论了你！','payload'=>$payload], $caseData['uid']);
            
            returnAjax($return,'评论成功',1);exit();
        }
    }
    
    /**
     * @获取评论列表
     */
    public function getCommentsList(){
        $get = input('post.');
        if(empty($get['case_id'])){
            returnAjax([],'缺少参数',2);exit();
        }
        
        $page = empty($get['page'])?1:$get['page'];
        $limit = empty($get['limit'])?10:$get['limit'];
        $data=[];
        $data = $this->caseCommentModel->getListData($get['case_id'],0,$page,$limit);
        
        $return = [];
        
        foreach($data as $k=>$v){
            $return[$k]=$v;
            
            if(empty($v['to_user_info'])){
                $return[$k]['to_user_info']=[];
            }
            
            $return[$k]['child_comment_count'] =$this->caseCommentModel->getChildCommentCount($get['case_id'],$v['id']);
            //是否允许删除按钮
           /* $return[$k]['isdel'] = 2;
            if($v['uid'] == $this->userData->id){
                $return[$k]['isdel'] = 1;
            }*/
            //是否允许回复按钮
            $child_comments = $this->caseCommentModel->getListData($get['case_id'],$v['id'],1,5);
            foreach($child_comments as $ck=>$cv){
                if(empty($cv['to_user_info'])){
                    $child_comments[$ck]['to_user_info']=[];
                }
                /*$child_comments[$ck]['isdel'] = 2;
                if($cv['uid'] == $this->userData->id){
                    $child_comments[$ck]['isdel'] = 1;
                }*/
            }
            $return[$k]['child_comments'] = $child_comments;
            
        }
        returnAjax(['page'=>$page,'data'=>$return],'获取评论数据成功！',1);exit();
        
    }
    
    /**
     * @获取子级评论列表
     */
    public function getChildCommentsList(){
        $get = input('post.');
        
        if(empty($get['case_id'])){
            returnAjax([],'缺少参数',2);exit();
        }
        if(empty($get['pid'])){
            returnAjax([],'缺少参数',2);exit();
        }
        
        $page = empty($get['page'])?1:$get['page'];
        $limit = empty($get['limit'])?10:$get['limit'];
        $data=[];
        $data = $this->caseCommentModel->getListData($get['case_id'],$get['pid'],$page,$limit);
        
        foreach($data as $ck=>$cv){
            /*$data[$ck]['isdel'] = 2;
            if($cv['uid'] == $this->userData->id){
                $data[$ck]['isdel'] = 1;
            }*/
            if(empty($cv['to_user_info'])){
                $data[$ck]['to_user_info']=[];
            }
        }
        returnAjax(['page'=>$page,'data'=>$data],'获取评论数据成功！',1);exit();
    }
    
    //删除评论
    public function delComment(){
        $get = input('post.');
        
        if(empty($get['id'])){
            returnAjax([],'缺少参数',2);exit();
        }
        $data = $this->caseCommentModel->getDataById($get['id']);
        if($data['uid'] != $this->userData->id){
            returnAjax([],'只能删除自己的评论',2);exit();
        }
        
        if($data['pid'] == '0'){            
                $this->caseModel->saveJComment($data['case_id']);
            }
            
        $this->caseCommentModel->delComment($get['id']);
        $this->caseCommentModel->delChildComment($get['id']);
        $datac = $this->caseModel->getDataById($data['case_id']);
   
        returnAjax(['count'=>$datac['comments']],'删除成功',1);exit();
    }
    
    /**
     * @发布病例秀
     */
    public function releaseCase(){
        $get = input('post.');
        
        if(empty($get['group_id'])){
            returnAjax([],'缺少参数',2);exit();
        }
        
        if(empty($get['contents'])){
            returnAjax([],'缺少参数',2);exit();
        }
        if(empty($get['type'])){
            returnAjax([],'缺少参数',2);exit();
        }
        
        $data=[
            'group_id'=>$get['group_id'],
            'contents'=>$get['contents'],
            'type'=>$get['type'],
            'uid'=>$this->userData->id,
            'creatime'=>time()
        ];
        
        $data = $this->caseModel->saveData($data);
        
        if($data['status'] == 2){
            returnAjax([],$data['msg'],2);exit();
        }
        
        if(!empty($get['images'])){
            //保存图片
            $images = [];
            foreach($get['images'] as $ik=>$kv){
                $images[]=['case_id'=>$data['id'],'image'=>$kv,'simage'=>$kv];
            }
            
            $case_image = new \app\tcyy\model\CasesImage();
            $imageData = $case_image->saveData($images);
         
            if(!$imageData){
                $this->caseModel->del($data['id']);
                returnAjax([],'发布失败',2);exit();
            }
        }   
        
        $return = $this->getDataById($data['id']);
        
        returnAjax($return,'发布成功',1);exit();
    }
    
    /**
     * @获取点赞人
     */
    public function getThingList(){
        $get = input('post.');
        
        if(empty($get['case_id'])){
            returnAjax([],'缺少参数',2);exit();
        }
        
        $this->caseThingModel = new \app\tcyy\model\CasesThing();
        $page = empty($get['page'])?1:$get['page'];
        $limit = empty($get['limit'])?10:$get['limit'];
        $data = $this->caseThingModel->getDataList($this->userData->id,$get['case_id'],$page,$limit);
        $return=[];
        foreach($data as $k=>$v){
            if(!empty($v['cases_follow']) && $v['cases_follow']['status'] == 1){
                 $v['cases_follow'] = 1;
            }else{
                 $v['cases_follow'] = 2;
            }
            $return[$k]=$v;
        }
        returnAjax(['page'=>$page,'data'=>$return],'获取点赞数据成功！',1);exit();
    }
    
    
    private function getDataById($id){
       
            $data = $this->caseModel->getDataByIDR($id,$this->userData->id);

            $return =[];
        
            if($data['uid'] == $this->userData->id){
                $return['isMe']=1;
            }else{
                $return['isMe']=2;
            }
            
            $return['id'] = $data['id'];
            $return['contents'] = $data['contents'];
            $return['thing'] = $data['thing'];
            $return['read'] = $data['read'];
            $return['comments'] = $data['comments'];
            $return['group_id'] = $data['group_id'];
            $return['creatime']= $data['creatime'];
            $return['userinfo'] = $data['user_info'];
            
            //是否关注  1关注  2非关注
            if(!empty($data['cases_follow']) && $data['cases_follow']['status'] == 1){
                    $return['isfollow'] = 1;
            }else{
                    $return['isfollow'] = 2;
            }
            
            if(empty($data['cases_image'])){$return['cases_image']=[];}else{$return['cases_image']=$data['cases_image']->toArray();};
        
            return $return;
    }
    
    /**
     * @发布草稿箱
     */
    public function releaseDraft(){
        $get = input('post.');
        if(empty($get['case_id'])){
            returnAjax([],'缺少参数',2);exit();
        }
        
        $return = $this->caseModel->fbcg($get['case_id']);
        if(!$return){
            returnAjax([],'发布失败',2);exit();
        }
        returnAjax([],'发布成功',1);exit();
    }
    
    /**
     * @删除草稿
     */
    public function delDraft(){
        $get = input('post.');
        if(empty($get['case_id'])){
            returnAjax([],'缺少参数',2);exit();
        }
        
        $return = $this->caseModel->delDraft($get['case_id']);
        if(!$return){
            returnAjax([],'删除失败',2);exit();
        }
        
        returnAjax([],'删除成功',1);exit();
    }
    
     public function pushtest(){
                $miCon = new \app\tcyy\controller\Mipush();
                $payload=['type'=>1];
                $data = $miCon->send(['title'=>'有人关注了你！','desc'=>$this->userData->auth_group->nickname.' 关注了你！','payload'=>$payload], 1883);
                
                dump($data);exit();
    }
}
