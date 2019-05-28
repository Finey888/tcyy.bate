<?php
namespace app\tcyy\controller;
use think\Request;
class Point extends Common
{
    private $pointShopModel;
    private $pointBookModel;
    private $userModel;
    private $pointUserModel;
    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->pointShopModel = new \app\tcyy\model\PointShop();
        $this->pointBookModel = new \app\tcyy\model\PointBook();
        $this->userModel = new \app\tcyy\model\User();
        $this->pointUserModel = new \app\tcyy\model\PointUser();
        
    }
    /**
     * @获取积分商城列表
     */
    public function getListData(){
        $get = input('post.');
        $page = empty($get['page'])?1:$get['page'];
        $limit = empty($get['limit'])?10:$get['limit'];
        $data = $this->pointShopModel->getDataList($page,$limit);
        
        returnAjax(['data'=>$data,'page'=>$page],'获取成功',1);exit();
    }
    
    /**
     * @商品详情
     */
    public function getDetails(){
        $get = input('post.');
        
        $data = $this->pointShopModel->getDataById($get['id']);
        
        $data['contents'] = html_entity_decode(html_entity_decode($data['contents'],ENT_QUOTES ),ENT_QUOTES );
        $hostStr = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'];
        $data['contents']=preg_replace('/(<img.+src=?.+)(\"\/)(.+\.\"?.+>)/i',"<img src=\"{$hostStr}/\${3}",$data['contents']);
        
        returnAjax($data,'获取成功',1);exit();
    }
    
    /**
     * @下单
     */
    public function saveBook(){
        $get = input('post.');
      
        if(empty($get['phone'])){
            returnAjax([],'请填写电话！',2);exit();
        }
        if(empty($get['realname'])){
            returnAjax([],'请填写姓名！',2);exit();
        }
//        if(empty($get['province'])){
//            returnAjax([],'请填写省份！',2);exit();
//        }
//        
//        if(empty($get['city'])){
//            returnAjax([],'请填市！',2);exit();
//        }
//        
//        if(empty($get['area'])){
//            returnAjax([],'请填区！',2);exit();
//        }
//        
        if(empty($get['address'])){
            returnAjax([],'请填详细地址！',2);exit();
        }
       
        //获取商品价格
        $pointData = $this->pointShopModel->getDataById($get['goodid']);
          
        $userData = $this->userModel->getDataById($this->userData->id);
        
        if($pointData['point']>$userData['point']){
            returnAjax([],'积分不足',2);exit();
        }
        
        if(($pointData['number']-$pointData['consume'])<=0){
            returnAjax([],'商品数量不足！',2);exit();
        }
        
        //扣除积分
        $return = $this->userModel->savePoint($this->userData->id, $pointData['point'],2);
        if(!$return){
            returnAjax([],'兑换失败',2);exit();
        }
        //添加积分操作
        $this->pointUserModel->addData(4, 2, $this->userData->id, $pointData['point'], $userData['point'],$get['goodid']);
        
        $lastData = $this->pointBookModel->getLastData();
        
        //创建订单
        $pram=[
            'goodid'=>$get['goodid'],
            'status'=>1,
            'uid'=>$this->userData->id,
            'createtime'=>time(),
            'phone'=>$get['phone'],
            'realname'=>$get['realname'],
            'point'=>$pointData['point'],
            'address'=>$get['address'],
            'booknum'=>'B'.date('YmdHis',time()).($lastData['id']+1)
        ];
        
        $data = $this->pointBookModel->saveData($pram);
        
        if(!$data){
            returnAjax([],'兑换失败！',2);exit();
        }
        //更新库存
        $this->pointShopModel->saveConsume($get['goodid']);
        
        returnAjax([],'兑换成功！',1);exit();
    }
    
    /**
     * @获取订到列表
     */
    public function getBookList(){
        $get = input('post.');
        $page = empty($get['page'])?1:$get['page'];
        $limit = empty($get['limit'])?10:$get['limit'];
        $data = $this->pointBookModel->getDataList($this->userData->id,$page,$limit);
        returnAjax(['data'=>$data,'page'=>$page],'获取成功',1);exit();
    }
    
    /**
     * @订单详情
     */
    public function getBookDetails(){
        $get = input('post.');
        $data = $this->pointBookModel->getDataById($get['id']);
        returnAjax($data,'获取成功',1);exit();
    }
    
    /**
     * @获取总积分
     */
    public function getTotalPoint(){
        $data = $this->userModel->getTotalPoint($this->userData->id);
        returnAjax($data,'获取成功',1);exit();
    }
}
