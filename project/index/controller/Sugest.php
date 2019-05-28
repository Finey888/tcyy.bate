<?php
namespace app\index\controller;
use think\Request;

//意见反馈
class Sugest extends Common
{
    private $model;
    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->model = new \app\index\model\Sugest();
    }
    
    public function index()
    {
        $get = input('get.');
        $where['status']=['neq',-1];
        if(!empty($get['status'])&&$get['status']!='0'){
            $where['status'] = $get['status'];
        }
        
        if(!empty($get['type'])){
            if($get['type'] == 1){
                $where['huifu'] = ['exp','is null'];
            }elseif($get['type'] == 2){
                $where['huifu'] = ['exp','is not null'];
            }
        }
        
        $count = $this->model->getCount($where);
        
        $this->assign('count',$count);
        
        return view('Sugest/index');
    }
    
    //获取已经反馈列表
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
        
        if(!empty($get['type'])){
            if($get['type'] == 1){
                $where['huifu'] = ['exp','is null'];
            }elseif($get['type'] == 2){
                $where['huifu'] = ['exp','is not null'];
            }
        }
        
        $data = $this->model->getPageData($page,$count,$where,$sort);
        
        return json(['data'=>$data,'status'=>1,'msg'=>'获取数据成功']);
    }
    
    //详情
    public function details(){
        $get = input('get.');
        $data = $this->model->getDataById($get['id']);
        $this->assign('data',$data);
        return view('Sugest/details');
    }
    
   /*
    * 保存数据
    */
   public function reply(){
       $data=[];
       if(request()->isPost()){ $data = input('post.'); }else{ return json(['status'=>-1,'msg'=>'未知数据']); }
        //添加数据
        $return = $this->model->addData(['huifu'=>$data['huifu'],'time2'=>time()],['id'=>$data['id']]);
        
        $str = '<div class="mui-card-content">
                        <div class="mui-card-content-inner">
                                <p>感谢您宝贵的建议！</p>
                                <p style="color: #333;">'.$data['huifu'].'</p>
                        </div>
                </div><br/><div class="mui-card-content">
                        <div class="mui-card-content-inner">
                                <p>您的建议：</p>
                                <p style="color: #333;">'.$data['contents'].'</p>
                        </div>
                </div>';
        
        $mesModel = new \app\index\model\Message();
        $mesModel->addData('回复了你的意见反馈',1, $data['uid'],'',$str,'');
                
        $mipush = new \app\index\controller\Mipush();
        
        $mipush->send(['title'=>'意见反馈','desc'=>'回复了你的意见反馈！','payload'=>['type'=>1]], $data['uid']);
        return ['status'=>1,'msg'=>'回复成功'];
        
        
   }
   
}
