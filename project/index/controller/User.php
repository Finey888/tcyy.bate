<?php
namespace app\index\controller;
use think\Request;

class User extends Common
{
    private $model;
    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->model = new \app\index\model\User();
    }
    public function index()
    {
        $get=input('get.');
        $where=[];
        $where['status']=['neq',-1];
        if(!empty($get['status']) && $get['status'] != '0'){$where['status']=$get['status'];}
        if(!empty($get['keyword'])){
            $where['userInfo.phone|userInfo.nickname|userInfo.realname'] = ['like', '%'.$get['keyword'].'%'];
        }
        
        if(!empty($get['is_vip'])&&$get['is_vip']!='0'){
            if($get['is_vip'] == 1 ){
                $where['vip_end_date'] = ['gt',time()];
            }else{
                $where['vip_end_date'] = ['lt',time()];
            }
        }
        
        if(!empty($get['type'])&&$get['type']!='0'){
            $where['type'] = $get['type'];
        }
        
        $countPage = $this->model->getCount($where);
        $this->assign('countPage',$countPage);
        return view('User/index');
    }
    
    //用户详情
    public function details(){   
        if(request()->isGet()){ $get = input('get.'); }else{ return json(['status'=>-1,'msg'=>'未知数据']); }
        if(empty($get['id'])){ return json(['status'=>-1,'msg'=>'未知数据']); }
        $data = $this->model->getDataById($get['id']);
        $this->assign('data',$data);

        return view('User/details');
    }
    
    //获取列表
    public function getList(){
        $get = input('get.');
        $page = empty($get['page'])?1:$get['page'];
        $count = empty($get['count'])?10:$get['count'];
        $sort='id desc';
        $where=[];
        $where['status']=['neq',-1];
        if(!empty($get['status'])&&$get['status']!='0'){
            $where['status'] = $get['status'];
        }
        
        if(!empty($get['is_vip'])&&$get['is_vip']!='0'){
            if($get['is_vip'] == 1 ){
                $where['vip_end_date'] = ['gt',time()];
            }else{
                $where['vip_end_date'] = ['lt',time()];
            }
        }
        
        if(!empty($get['type'])&&$get['type']!='0'){
            $where['type'] = $get['type'];
        }
        
        if($get['sort'] == 1){
            $sort = 'id desc';
        }elseif($get['sort'] == 2){
            $sort = 'last_login_time desc';
        }elseif($get['sort'] == 3){
            $sort = 'login_count desc';
        }
        
        
        if(!empty($get['keyword'])){
            $where['userInfo.realname|userInfo.phone|userInfo.nickname'] = ['like', '%'.$get['keyword'].'%'];
        }
   
        $data = $this->model->getPageData($page,$count,$where,$sort);
        
        return json(['data'=>$data->toArray(),'status'=>1,'msg'=>'获取数据成功']);
    }
    
    /**
     * 修改状态
     * @param int $id 数据ID
     * @param int $status 状态
     * @return array 返回操作状态
     */
   public function editStatus(){
        $id='';
        if(request()->isGet()){ $get = input('get.'); }else{ return json(['status'=>-1,'msg'=>'未知数据']); }
        if(empty($get['id'])){ return json(['status'=>-1,'msg'=>'未知数据']); }
        if(empty($get['status'])){ return json(['status'=>-1,'msg'=>'未知数据']); }
        $return = $this->model->setStatus($get['id'],$get['status']);
        return $return==1?['status'=>1,'msg'=>'修改成功']:['status'=>-1,'msg'=>'修改失败'];
   }
   
   /**
     * 修改状态
     * @param int $id 数据ID
     * @param int $status 状态
     * @return array 返回操作状态
     */
   public function editTypeStatus(){
        $id='';
        if(request()->isGet()){ $get = input('get.'); }else{ return json(['status'=>-1,'msg'=>'未知数据']); }
        if(empty($get['id'])){ return json(['status'=>-1,'msg'=>'未知数据']); }
        if(empty($get['status'])){ return json(['status'=>-1,'msg'=>'未知数据']); }
        $return = $this->model->setType($get['id'],$get['status']);
        return $return==1?['status'=>1,'msg'=>'修改成功']:['status'=>-1,'msg'=>'修改失败'];
   }
   
   /**
     * 修改会员时间
     * @param int $id 数据ID
     * @param int $enddate 结束时间
     * @return array 返回操作状态
     */
   public function editVipDate(){
        $id='';
        
        if(request()->isGet()){ $get = input('get.'); }else{ return json(['status'=>-1,'msg'=>'未知数据']); }
        
        if(empty($get['id'])){ return json(['status'=>-1,'msg'=>'未知数据']); }
        
        if(empty($get['enddate'])){ return json(['status'=>-1,'msg'=>'未知数据']); }
        
        $return = $this->model->setVipDate($get['id'],$get['enddate']);
       
        return empty($return)?['status'=>1,'msg'=>'修改成功']:['status'=>-1,'msg'=>$return];
   }
   
}
