<?php
namespace app\index\model;

use app\index\model\Common;
use think\Model;

class PersonalPosition extends Common {
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
    public function getPageData($page=1,$count=10,$where=['isdel'=>['neq',1]],$sort='id desc'){
        $data = $this::Query('SELECT cm.name,cm.email,pt.address,pt.positiontype,pt.region,pt.professional,pt.status,pt.wages,pt.experience,pt.education FROM tcyy_personal_company cm,tcyy_personal_position pt on cm.id = pt.cid')->where($where)->page($page.','.$count)->order($sort)->select();
        return $data;
    }

}	