<?php
namespace app\tcyy\controller;

use think\Request;

class Basedict extends Base{
	protected $model = null;

    protected $personalCompanymodel = null;

    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->model = new \app\tcyy\model\BaseDict;
    }
     //id单个查询
    public function info(){
        $base_dict = $this->model->info($this->request);
        $this->assign('base_dict', $base_dict);
        return $this->fetch();
    }

	//字典列表
	public function lists(){
		$base_dictList = $this -> model -> lists();
        $result = $base_dictList -> toArray();
        returnAjax($result,'获取数据成功',1);exit();
	}
}
