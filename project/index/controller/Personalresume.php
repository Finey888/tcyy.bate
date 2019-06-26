<?php
namespace app\index\controller;

use think\Request;

class Personalresume extends Common {
	protected $model = null;

    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->model = new \app\index\model\PersonalResume;
    }

    //默认入口
    public function index(){
        $get = input('get.');
        $where = [];
        $where['pt.isdel'] = ['neq',1];

        if(!empty($get['companyName'])){
            $where['cm.name'] = ['like', '%'.$get['companyName'].'%'];
        }

        $countPage = $this->model->getCount($where);
        $this->assign('countPage',$countPage);
        return view('Personalposition/index');
    }

		//新增
	public function add(){
		if($this->request->isPost()){
			$flag = $this->model->add($this->request);
			if($flag){
				$this->success('添加成功', url('lists'));
			}else{
				$this->error('添加失败');
			}
		}else{
			return $this->fetch();
		}
	}
		//修改
	public function edit(){
		if($this->request->isPost()){
			$flag = $this->model->edit($this->request);
			if($flag){
				$this->success('编辑成功', url('lists'));
			}else{
				$this->error('编辑失败');
			}
		}else{
			$personal_resume = $this->model->info($this->request);
			$this->assign('personal_resume', $personal_resume);
			return $this->fetch();
		}
	}
		//删除
	public function delete(){
		$flag = $this->model->del($this->request);
		if($flag){
			$this->success('删除成功', url('lists'));
		}else{
			$this->error('删除失败');
		}
	}
	    //批量删除
    public function delList(){
        $flag = $this->model->delList($this->request);
        if($flag){
			$this->success('删除成功', url('lists'));
		}else{
			$this->error('删除失败');
		}
    }
	    //id单个查询
    public function info(){
        $personal_resume = $this->model->info($this->request);
        $this->assign('personal_resume', $personal_resume);
        return $this->fetch();
    }
		//列表
	public function lists(){
		$personal_resumeList = $this->model->lists($this->request, 12);	
		$this->assign('personal_resumeList', $personal_resumeList);
		return $this->fetch();
	}
}
