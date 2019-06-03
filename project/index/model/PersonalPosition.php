<?php
namespace app\index\model;


use think\Db;

class PersonalPosition extends Common {


    // 设置数据表（不含前缀）
    protected $name = 'personal_position';

    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->_collection = Db::name($this->name);
    }

		    //新增
    public function add($request){
        $data = $request->param();
        foreach($data as $key=>$val){
            if(is_array($val)){    //处理checkbox情况
                $data[$key] = implode("#op#", $val);
            }
        }
        return $this->data($data)->allowField(true)->save();
    }
	    //修改
    public function edit($request){
        $data = $request->param();
        foreach($data as $key=>$val){
            if(is_array($val)){    //处理checkbox情况
                $data[$key] = implode("#op#", $val);
            }
        }
        return $this->allowField(true)->save($data, ['id' => $data['id']]);
    }
	    //删除
    public function del($request){
        $id = $request->param('id');
        return $this->where('id',  $id)->delete();
    }
	    //批量删除
    public function delList($request){
        $condition = $request->request('condition');
        return $this->destroy(json_decode($condition));
    }
	    //id单个查询
    public function info($request){
        $id = $request->param('id');		
        return $this->where('id', $id)->find();
    }
	    //列表
    public function lists($request, $itemNum = 12){	//每页显示12条数据
        $condition = $request->param('condition');
        return $this->where(json_decode($condition))->paginate($itemNum);
    }

    //分页数据
    public function getPageData($page=1,$count=10,$where=[],$sort='pt.creatime desc'){
//        $data = $this :: with('personalCompanyInfo') -> field('id,cid,address,positiontype,region,professional,status,wages,experience,education')->where($where)->page($page.','.$count)->order($sort)->select();
        $data = $this ->_collection
            ->alias('pt')
            ->where($where)
            ->join('tcyy_personal_company cm', ' cm.id = pt.cid', 'inner')
            ->field('pt.id,cm.name,cm.email,pt.address,pt.positiontype,pt.region,pt.professional,pt.status,pt.wages,pt.experience,pt.education ')->page($page.','.$count)->order($sort)->select();
        return $data;
    }

    //获取总条数
    public function getCount($where=[]){
//        return $this->where($where)->count();
//        return $data = $this :: with('personalCompanyInfo') -> field('id,cid,address,positiontype,region,professional,status,wages,experience,education')->where($where)->count();
        return  $this ->_collection
            ->alias('pt')
            ->where($where)
            ->join('tcyy_personal_company cm', 'cm.id = pt.cid', 'inner')
            ->field('pt.id,cm.name,cm.email,pt.address,pt.positiontype,pt.region,pt.professional,pt.status,pt.wages,pt.experience,pt.education ') -> count();
    }

//    public function personalCompanyInfo()
//    {
//        return $this->belongsTo('PersonalCompany')->field('id,name,email');
//    }
}	