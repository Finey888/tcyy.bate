<?php
namespace app\tcyy\controller;
use think\Request;
class Project extends Common
{
    private $projectModel;
    private $projectDetailsModel;
    private $projectUserModel;
    private $projectPriceModel;
    private $projectTimingModel;
    private $zlxmModel;
    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->projectModel = new \app\tcyy\model\Project();
        $this->projectDetailsModel = new \app\tcyy\model\ProjectDetails();
        $this->projectUserModel = new \app\tcyy\model\ProjectUser();
        $this->projectPriceModel = new \app\tcyy\model\ProjectPrice();
        $this->projectTimingModel = new \app\tcyy\model\ProjectTiming();
        $this->zlxmModel = new \app\tcyy\model\Zlxm();
    }
    
    /**
     * @项目启动页图片
     */
    public function getStartImg(){
        $data = $this->zlxmModel->getDataFind();
        if(empty($data)){$data='';}else{$data = $data->toArray();};
        returnAjax($data,'获取数据成功',1);exit();
    }
    
    /**
     * @获取系统项目产品和用户自定义产品
     */
    public function getList()
    {
       $get = input('post.');
       $page = empty($get['page'])?1:$get['page'];
       $limit = empty($get['limit'])?10:$get['limit'];
       $group_id = empty($get['groupid'])? returnAjax([],'缺少参数',2):$get['groupid'];
       $data = $this->projectModel->getDataByUid($this->userData->id,$group_id,$page,$limit);
       foreach ($data as $k=>$v){
           unset($data[$k]['project_price']);
           if(!empty($data[$k]['userPrice'])){
               $data[$k]['price'] = $data[$k]['userPrice'];
           }
           unset($data[$k]['userPrice']);
       }
       returnAjax(['data'=>$data,'page'=>$page],'获取数据成功',1);exit();
    }
    
    /**
     * @添加用户项目
     * @param string $number 编号
     * @param int $price 价钱
     * @param string $title 标题
     * @param int $group_id 分类ID
     * @param int $uid 分类ID
     * @param string $image 图片
     */
    public function saveData(){
        $get = input('post.');
        if(empty($get['id'])){
            $data['group_id'] = empty($get['groupid'])? returnAjax([],'缺少参数',2):$get['groupid'];
            $data['number'] = empty($get['number'])? returnAjax([],'缺少参数',2):$get['number'];
            $data['price'] = empty($get['price'])? returnAjax([],'缺少参数',2):$get['price'];
            $data['title'] = empty($get['title'])? returnAjax([],'缺少参数',2):$get['title'];
            $data['image'] = empty($get['image'])? returnAjax([],'缺少参数',2):$get['image'];
            $data['status'] = 1;
            $data['uid'] = $this->userData->id;
            $return = $this->projectModel->addData($data);
            if($return['status'] == 2){
                returnAjax([], $return['msg'],2);
            }

            $rtd=[
                'id'=>$return['data']['id'],
                'number'=>$return['data']['number'],
                'price'=>$return['data']['price'],
                'image'=>$return['data']['image'],
                'title'=>$return['data']['title']
            ];
            returnAjax($rtd, '添加成功',1);
        }else{
            $return = $this->projectModel->addData($get,['id'=>$get['id']]);
            if($return['status'] == 2){
                returnAjax([], $return['msg'],2);
            }
            
            $data = $this->projectModel->getDataById($get['id']);
            
            $rtd=[
                'id'=>$data['id'],
                'number'=>$data['number'],
                'price'=>$data['price'],
                'image'=>$data['image'],
                'title'=>$data['title']
            ];
            
            returnAjax($rtd, '修改成功',1);
        }
    }
    
    /**
     * @修改项目价格
     * @param int $price 价钱
     * @param int $id 数据ID
     * @param int $system -1 系统项目  other 用户项目
     */
    public function editPrice(){
        $get = input('post.');
        $id = empty($get['id'])? returnAjax([],'缺少参数',2):$get['id'];
        $price = empty($get['price'])? returnAjax([],'缺少参数',2):$get['price'];
        
        $priceData = $this->projectModel->getDataById($id);
        if($priceData['uid'] != -1){
            $data = $this->projectModel->addData(['price'=>$price],['id'=>$id]);
            if($data['status'] == 2){
                returnAjax($data['data'],$data['msg'],2);
            }
        }else{
            //查询是否已经存在
            $pp = $this->projectPriceModel->getDataByUidPid($id,$this->userData->id);
            if(empty($pp)){
                //新增
                $data = $this->projectPriceModel->saveData(['price'=>$price,'pid'=>$id,'uid'=>$this->userData->id]);
                if($data['status'] == 2){
                    returnAjax($data['data'],$data['msg'],2);
                }
            }else{
                //修改
                $data = $this->projectPriceModel->saveData(['price'=>$price],['id'=>$pp['id']]);
                if($data['status'] == 2){
                    returnAjax($data['data'],$data['msg'],2);
                }
            }
        }
        returnAjax([],'修改成功',1);
    }
    
    /**
     * @删除项目
     */
    public function del(){
        $get = input('post.');
        $id = empty($get['id'])? returnAjax([],'缺少参数',2):$get['id'];
        
        $priceData = $this->projectModel->getDataById($id);
        
        if(empty($priceData)){
            returnAjax([],'未知数据',2);exit();
        }
        
        if($priceData['uid'] == -1){
            returnAjax([],'系统项目无法删除',2);exit();
        }
        
        $data = $this->projectModel->del($id,$this->userData->id);

       if(empty($data)){
           returnAjax([],'删除失败',2);exit();
       }
       
       returnAjax([],'删除成功',1);exit();
    }
    
    
    /**
     * @保存项目计划
     */
    public function savePlan(){
        $get = input('post.');

        $data = empty($get['data'])? returnAjax([],'缺少参数',2):$get['data'];
        
        empty($get['sex'])? returnAjax([],'缺少参数',2):$get['sex'];
        empty($get['name'])? returnAjax([],'缺少参数',2):$get['name'];
        empty($get['age'])? returnAjax([],'缺少参数',2):$get['age'];
        empty($get['phone'])? returnAjax([],'缺少参数',2):$get['phone'];
        empty($get['booktime'])? returnAjax([],'缺少参数',2):$get['booktime'];
        
        
        //组装产品数据
        $insertData=[];
        foreach($data as $k=>$v){
                $insertData[$k]=$v;
        }
        
        //添加项目
        $return = $this->projectDetailsModel->saveAllData($insertData);
        
        if($return['status'] == 2){
            returnAjax([],$return['msg'],2);
        }
        
        $ids=[];//保存idstring
        $price=0;//总价
        foreach($return['data'] as $k=>$v){
                $da = $v->toArray();
                $ids[] = $da['id'];
                $price+=$da['price']*$v['num'];
            }
        
            //组装项目订单数据
            $projectData=[
                'name'=>$get['name'],
                'sex'=>$get['sex'],
                'age'=>$get['age'],
                'booktime'=>$get['booktime'],
                'uid'=>$this->userData->id,
                'pids'=>implode(',',$ids),
                'price'=>$price,
                'phone'=>$get['phone'],
                'zzys'=>$get['zzys'],
                'jzs'=>$get['jzs'],
                'image'=>$get['image']
            ];
            
            //添加项目患者
            $userReturn = $this->projectUserModel->addData($projectData);
            
            if($userReturn['status'] == 2){
                //project_details 失败 删除
                $this->projectDetailsModel->delByIds(implode(',',$ids));
                returnAjax([],$userReturn['msg'],2);
            }
            
            //添加项目预约时间和备注
            $timingData=[
                'puid'=>$userReturn['data'],
                'times'=> strtotime($get['booktime']),
                'status'=>1,
                'createtime'=>time()
            ];
            $timeReturn = $this->projectTimingModel->saveData($timingData);
            if($timeReturn['status'] == 2){
                //project_user 失败 删除
                $this->projectUserModel->del($userReturn['data']);
                //project_details 失败 删除
                $this->projectDetailsModel->delByIds(implode(',',$ids));
                returnAjax([],$timeReturn['msg'],2);
            }
            
            returnAjax([],'保存成功',1);
    }
    
    /**
     * @添加下次预约时间
     */
    public function addNextBookTime(){
        $get = input('post.');
        $puid = empty($get['puid'])? returnAjax([],'缺少参数',2):$get['puid'];
        $time = empty($get['booktime'])? returnAjax([],'缺少参数',2):$get['booktime'];
        
        //判断上次预约是否完成
        $LastData = $this->projectTimingModel->getDataByPuid($puid);
      
        if(!empty($LastData) && $LastData['status'] == 1){
            returnAjax([],'上次预约未处理！不能新增预约',2);
        }
        
        //添加项目预约时间和备注
        $timingData=[
            'puid'=>$puid,
            'times'=> strtotime($time),
            'status'=>1,
            'createtime'=>time()
        ];
        $timeReturn = $this->projectTimingModel->saveData($timingData);
        
        if($timeReturn['status'] == 2){
            returnAjax([],$timeReturn['msg'],2);
        }
        
        $this->projectUserModel->editBookTime($puid, strtotime($time));
        
        $this->projectUserModel->editType($puid, 1);
        
        returnAjax([],'保存成功',1);
    }
    
    /**
     * @修改预约时间
     */
    public function editTimingTime(){
        $get = input('post.');
        $puid = empty($get['puid'])? returnAjax([],'缺少参数',2):$get['puid'];
        $time = empty($get['booktime'])? returnAjax([],'缺少参数',2):$get['booktime'];
        
        //判断上次预约是否完成
        $LastData = $this->projectTimingModel->getDataByPuid($puid);
        
        if($LastData['status'] == 2 || $LastData['status'] == 3){
            returnAjax([],'预约已经生效,不能修改！',2);
        }
        
        //添加项目预约时间和备注
        $timingData=[
            'puid'=>$puid,
            'times'=> strtotime($time),
            'status'=>1,
            'createtime'=>time()
        ];
        $timeReturn = $this->projectTimingModel->saveData($timingData);
        
        if($timeReturn['status'] == 2){
            returnAjax([],$timeReturn['msg'],2);
        }
        
        $this->projectUserModel->editBookTime($puid, strtotime($time));
        
        returnAjax([],'保存成功',1);
    }


    /**
     * @修改就诊状态
     */
    public function editProjectUserStatus(){
        $get = input('post.');
        $id = empty($get['id'])? returnAjax([],'缺少参数',2):$get['id'];
        $status = empty($get['status'])? returnAjax([],'缺少参数',2):$get['status'];
        
        $data = $this->projectTimingModel->getDataById($id);
        
        $timeReturn = $this->projectTimingModel->editStatus($data['id'],$status);

        if(!$timeReturn){
            returnAjax([],'操作失败',2);
        }
        
        $this->projectUserModel->editType($data['puid'],$status);
        
        returnAjax([],'操作成功',1);
    }
    
    public function delProjectUser(){
        $get = input('post.');
        $id = empty($get['id'])? returnAjax([],'缺少参数',2):$get['id'];
        $this->projectUserModel->del($id);
        returnAjax([],'操作成功',1);
    }
    
    /**
     * @获取患者预约列表
     */
    public function getPatientList(){
        $get = input('post.');
        $page = empty($get['page'])?1:$get['page'];
        $limit = empty($get['limit'])?10:$get['limit'];
        $status = empty($get['status'])? '':$get['status'];
        $times = empty($get['times'])? '':$get['times'];
        
        $data = $this->projectUserModel->getDataList($this->userData->id,$status,$page,$limit,$times);
      
        returnAjax(['data'=>$data,'page'=>$page],'获取数据成功',1);exit();
    }
    
    /**
     * @获取患者详情
     */
    public function getPatientDetails(){
        $get = input('post.');
        $id = empty($get['id'])? returnAjax([],'缺少参数',2):$get['id'];
        $data = $this->projectUserModel->getDataDetails($id);

        $dataDetails = $this->projectDetailsModel->getDataByIds($data['pids']);
     
        $project = [];
        foreach($dataDetails as $k=>$v){
            $pd=[];
            $pd['price'] = $v['price'];
            $pd['num'] = $v['num'];
            $pd['title'] = $v['project']['title'];
            $pd['number'] = $v['project']['number'];
            $project[] = $pd;
        }
        
        $return=[
            'id'=>$data['id'],
            'name'=>$data['name'],
            'sex'=>$data['sex'],
            'age'=>$data['age'],
            'phone'=>$data['phone'],
            'booktime'=>$data['booktime'],
            'totalprice'=>$data['price'],
            'status'=>$data['type'],
            'tid'=>$data['project_timing_one']['id'],
            'project'=>$project,
            'zzys'=>$data['zzys'],
            'jzs'=>$data['jzs'],
            'image'=>$data['image']
        ];
        returnAjax($return,'获取数据成功',1);exit();
    }
}
