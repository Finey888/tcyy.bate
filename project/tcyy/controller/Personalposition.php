<?php
namespace app\tcyy\controller;
use think\Request;

class Personalposition extends Common {
	protected $model = null;

    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $this->model = new \app\tcyy\model\PersonalPosition;
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

    //获取列表
    public function queryPositionByCondition(){
        $get = input('post.');
        $page = empty($get['page'])?1:$get['page'];
        $limit = empty($get['limit'])?10:$get['limit'];
        $professional = empty($get['professional'])? '':$get['professional'];
        $begintime = empty($get['begintime'])? '':$get['begintime'];
        $endtime = empty($get['endtime'])? '':$get['endtime'];
        $region = empty($get['region'])? '':$get['region'];

        $where=[];
        $where['pt.status']=['eq',1];
        $where['pt.isdel']=['eq',0];

        if(!empty($professional)){
            $where['pt.professional'] = ['like', '%'.$professional.'%'];
        }
        if(!empty($region)){
            $where['pt.region'] = ['like', '%'.$region.'%'];
        }
        if(!empty($begintime) && !empty($endtime)) {
            $beginDate = strtotime($begintime);
            $endtime = strtotime($endtime);
            $where[] = ['exp', 'pt.lasttime >= ' . $beginDate . ' and pt.lasttime <= ' . $endtime];
        }
        $data = $this->model->getPageData($page,$limit,$where);

        returnAjax(['data'=>$data,'page'=>$page],'获取数据成功',1);exit();
    }


}
