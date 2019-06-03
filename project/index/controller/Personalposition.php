<?php
namespace app\index\controller;
use think\Request;

class Personalposition extends Common {
	protected $model = null;

    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->model = new \app\index\model\PersonalPosition;
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
			$personal_position = $this->model->info($this->request);
			$this->assign('personal_position', $personal_position);
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
        $personal_position = $this->model->info($this->request);
        $this->assign('personal_position', $personal_position);
        return $this->fetch();
    }
		//列表
	public function lists(){
		$personal_positionList = $this->model->lists($this->request, 12);	
		$this->assign('personal_positionList', $personal_positionList);
		return $this->fetch();
	}

    //获取列表
    public function getList(){
        $get = input('get.');
        $page = empty($get['page'])?1:$get['page'];
        $count = empty($get['count'])?10:$get['count'];

        $where=[];
        $where['pt.isdel']=['neq',1];

        if(!empty($get['companyName'])){
            $where['cm.name'] = ['like', '%'.$get['companyName'].'%'];
        }

        $data = $this->model->getPageData($page,$count,$where);

        return json(['data'=>$data,'status'=>1,'msg'=>'获取数据成功']);
    }


}
