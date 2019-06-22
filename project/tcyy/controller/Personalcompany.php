<?php
namespace app\tcyy\controller;

use think\Request;

class Personalcompany extends Common
{
    protected $personalCompanymodel = null;

    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->personalCompanymodel = new \app\tcyy\model\PersonalCompany();
    }

    /**
     * @添加会员企业信息
     * @param string $name 公司名称
     * @param string $offcialcurl 公司官网
     * @param string $logo logoUrl地址
     * @param int $registermoney  注册资金
     * @param int $createrdate 成立日期
     * @param int $people 人数规模
     * @param string $region 所在区域(省市区/县)
     * @param string $contacts 联系人
     * @param string $email 邮件地址
     * @param string $phone 联系电话
     * @param string $companyinfo 公司简介
     * @param int $uid 关联用户id
     * @param string $authurl 营业执照图片
     * @param string $address 详细地址
     */
    public function saveCompanyByVip(){
        $get = input('post.');
        if(empty($get['id'])){
            $data['name'] = empty($get['name'])? returnAjax([],'缺少公司名称参数',2):$get['name'];
            $data['region'] = empty($get['region'])? returnAjax([],'缺少区域参数',2):$get['region'];
            $data['createrdate'] = empty($get['createrdate']) ? returnAjax([],'缺少公司成立日期参数',2):strtotime(date('Y-m-d',strtotime($get['createrdate'])));
            $data['contacts'] = empty($get['contacts'])? returnAjax([],'缺少联系人参数',2):$get['contacts'];
            $data['email'] = empty($get['email'])? returnAjax([],'缺少邮件地址参数',2):$get['email'];
            $data['people'] = $get['people'];
            $data['address'] = $get['address'];
            $data['authurl'] = $get['authurl'];
            $data['registermoney'] = $get['registermoney'];
            $data['companyinfo'] = $get['companyinfo'];
            $data['offcialcurl'] = $get['offcialcurl'];
            $data['uid'] = $this->userData->id;
            $return = $this->personalCompanymodel->addData($data);
            if($return['status'] == 2){
                returnAjax([], $return['msg'],2);
            }

            $rtd=[
                'id'=>$return['data']['id'],
                'name'=>$return['data']['name'],
                'region'=>$return['data']['region']
            ];
            returnAjax($rtd, '增加成功',1);
        }else{
            $return = $this->personalCompanymodel->addData($get,['id'=>$get['id']]);
            if($return['status'] == 2){
                returnAjax([], $return['msg'],2);
            }

            $data = $this->personalCompanymodel->getDataById($get['id']);

            $rtd=[
                'id'=>$data['id'],
                'name'=>$data['name'],
                'region'=>$data['region']
            ];

            returnAjax($rtd, '更新成功',1);
        }
    }

    /**
     * 查询当前vip用户企业接收到的投递职位简历列表
     */
    public function viewDeliverResumes(){
        $get = input('post.');
        $page = empty($get['page'])?1:$get['page'];
        $limit = empty($get['limit'])?10:$get['limit'];
        $uid= $this->userData->id;
        $data = $this -> personalCompanymodel -> queryList($uid);

        returnAjax($data,"查询列表获取成功",1);
    }


    /**
     * 会员企业查询浏览简历历史
     */
    public function queryViewLogs(){
        $get = input('post.');
        $page = empty($get['page'])?1:$get['page'];
        $limit = empty($get['limit'])?10:$get['limit'];
        $pageStart = ($page - 1) * $limit;
        $paginationStr = ' limit '.$pageStart.','.$limit;
        $uid= $this->userData->id;
        $data = $this -> personalCompanymodel -> queryViewResumeLogs($uid,'',$paginationStr);
        returnAjax($data,"查询成功",1);
    }


    /**
     * 会员企业查询收藏简历记录
     */
    public function queryCollectResumeRecords(){
        $get = input('post.');
        $page = empty($get['page'])?1:$get['page'];
        $limit = empty($get['limit'])?10:$get['limit'];
        $pageStart = ($page - 1) * $limit;
        $paginationStr = ' limit '.$pageStart.','.$limit;
        $uid= $this->userData->id;
        $data = $this -> personalCompanymodel -> queryViewResumeLogs($uid,1,$paginationStr);
        returnAjax($data,"查询成功",1);
    }

    //根据主键编号查询公司详情
    public function queryCompanyDetail(){
        $get = input('post.');
        $id = $get['id'];
        if(empty($id)){
            returnAjax([], "非法参数",2);
        }
        $data = $this -> personalCompanymodel -> getDataById($id);
        returnAjax($data,"查询成功",1);
    }
}