<?php
namespace app\index\controller;
use think\Request;

class Menu extends Common
{
    private $model;
    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->model = new \app\index\model\Menu();
    }
    public function index()
    {
        return view('Menu/index');
    }
    
    //添加菜单
    public function add(){
        $parentData = $this->model->getMenuByPid(0);//一级菜单
        $this->assign('menu',$parentData);
        return view('Menu/add');
    }
    
    //修改菜单
    public function edit(){
        $id = input('get.id');
        $parentData = $this->model->getMenuByPid(0);//一级菜单
        $this->assign('menu',$parentData);
        
        $data = $this->model->getDataById($id);//一级菜单
        $data['icon'] = htmlentities($data['icon']);
        $this->assign('data',$data);
        return view('Menu/edit');
    }
    
    //获取列表
    public function getList(){
        $parentData = $this->model->getMenuByPid(0);//一级菜单
        foreach($parentData as $k=>$v){
            $parentData[$k]['childs'] = $this->model->getMenuByPid($v['id']);//一级菜单
            $count = count($parentData[$k]['childs']);
            foreach($parentData[$k]['childs'] as $ck=>$cv){
                    ($ck+1) == $count?$parentData[$k]['childs'][$ck]['lab'] = '└':
                                      $parentData[$k]['childs'][$ck]['lab'] = '├';
            }
        }
        return ['data'=>$parentData,'status'=>1,'msg'=>'数据获取成功'];
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
   
   /*
    * 保存数据
    */
   public function saveData(){
       $data=[];
       if(request()->isPost()){ $data = input('post.'); }else{ return json(['status'=>-1,'msg'=>'未知数据']); }
       if(empty($data['id'])){
           //添加数据
           $return = $this->model->saveData($data);
           return empty($return)?['status'=>1,'msg'=>'添加成功']:['status'=>-1,'msg'=>$return];
       }else{
           //修改数据
           $id = $data['id'];
           unset($data['id']);
           $return = $this->model->saveData($data,['id'=>$id]);
           return empty($return)?['status'=>1,'msg'=>'修改成功']:['status'=>-1,'msg'=>$return];
       }
   }
   
   /*
    * 删除数据
    */
   public function del(){
       if(request()->isPost()){ $id = input('post.id'); }else{ return json(['status'=>-1,'msg'=>'未知数据']); }
       
       $childData = $this->model->getMenuByPid($id);//是否还存在下级
       if(!empty($childData)){ return ['status'=>-1,'msg'=>'还存在下级数据！暂无法删除！']; }
       
       $return = $this->model->delById($id);
       
       return $return?['status'=>1,'msg'=>'删除成功']:['status'=>-1,'msg'=>'删除失败'];
   }
}
