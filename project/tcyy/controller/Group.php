<?php
namespace app\tcyy\controller;
use think\Request;
class Group extends Base
{
    private $groupModel;
    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->groupModel = new \app\tcyy\model\Group();
    }
    
    /**
     * @获取基础分类
     */
    public function getList()
    {
       $data = $this->groupModel->getDataByPid(0,0);
       returnAjax($data->toArray(),'获取数据成功',1);exit();
    }
    
    /**
     * @获取图片文件夹
     */
    public function getPictureFolder()
    {
        $get = input('post.');
        if(empty($get['groupid'])){
            returnAjax([],'缺少参数',2);exit();
        }
       $data = $this->groupModel->getDataByPid($get['groupid'],1);
       returnAjax($data->toArray(),'获取数据成功',1);exit();
    }
    
    /**
     * @获取用户文件夹
     */
    public function getUserFolder(){
        $get = input('post.');
        $com = new \app\tcyy\controller\Common();
        $user = $com->getUserData();
        if(empty($get['groupid'])){
            returnAjax([],'缺少参数',2);exit();
        }
        $folder = new \app\tcyy\model\UserFolder();
        $data = $folder->getDataList($user->id,$get['groupid']);
        returnAjax($data,'获取数据成功',1);exit();
    }
    
    /**
     * @修改文件夹名称
     */
    public function editFolder(){
        $get = input('post.');
        $com = new \app\tcyy\controller\Common();
        $user = $com->getUserData();
        if(empty($get['id'])){
            returnAjax([],'缺少参数',2);exit();
        }
        if(empty($get['title'])){
            returnAjax([],'缺少参数',2);exit();
        }
        $folder = new \app\tcyy\model\UserFolder();
        $data = $folder->editName($get,$user->id);
        returnAjax($data,'修改成功',1);exit();
    }
}
