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
        if(!empty($get['stats']) && $get['stats'] != '-'){
            $where['pt.status'] = ['eq', $get['stats']];
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


	//查询详情
    public function viewDetails(){
        if(request()->isGet()){
            $get = input('get.');
        }else{
            return json(['status'=>-1,'msg'=>'未知数据']);
        }

        if(empty($get['id'])){
            return json(['status'=>-1,'msg'=>'未知数据']);
        }

        $data = $this-> model -> getDataById($get['id']);
        $this->assign('data',$data);
        return view('Personalposition/details');
    }

    //获取列表
    public function getList(){
        $get = input('get.');
        $page = empty($get['page'])?1:$get['page'];
        $count = empty($get['count'])?10:$get['count'];

        $where = ' ';
        $where .= 'pt.isdel = 0';

        if(!empty($get['companyName'])){
            $where.= ' and cm.name like \'%'.$get['companyName'].'%\'';
        }
        if($get['stats'] != '-'){
            $where.= ' and pt.status = '.$get['stats'];
        }

        $data = $this->model->getPageData($page,$count,$where);

        return json(['data'=>$data,'status'=>1,'msg'=>'获取数据成功']);
    }

    public function auditPosition(){
        if(request()->isPost()){ $id = input('post.id'); }else{ return json(['status'=>-1,'msg'=>'未知数据']); }
        $stats = input('post.stats');
        $return = $this->model->auditPositionById($id,$stats);

        return $return ? ['status'=>1,'msg'=>'审核完成'] : ['status'=>-1,'msg'=>'审核失败'];
    }


}
