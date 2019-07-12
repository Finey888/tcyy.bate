<?php
namespace app\tcyy\controller;
use think\Request;

class Area extends Base {
	protected $model = null;

    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
		$this->model = new \app\tcyy\model\Area();
	}

	//获取省市区县列表
	public function getAreaList(){
//        $pid = input('pid'); //get方法
//        var_dump(input('post.'));
////        if($pid != '0'){
//            if(!isset($pid)){
//                returnAjax([],'缺少参数',2);exit();
//            }
////        }
//		$list = $this -> model -> getListByParentId($pid);
//        $result = $list->toArray();
//        returnAjax($result,'获取数据成功',1);exit();
//        dump((date('Y-m-d',time())));
//        dump(strtotime(date('Y-m-d',strtotime('2019-06-12'))));
        $get = input('post.');
//        if(!isset($get['pid'])){
//            returnAjax([],'缺少参数',2);exit();
//        }
//		$list = $this -> model -> getListByParentId($get['pid']);
        $list = $this -> model -> getAreaList();
        $result = $list->toArray();
        returnAjax($result,'获取数据成功',1);exit();
	}
}
