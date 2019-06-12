<?php
namespace app\tcyy\model;

class PersonalCompany extends Common {
    public function addData($data,$where=[]){
//        if(empty($where)){
//            $saveType = 'Personalcompany.add';
//        }else{
//            $saveType = 'Personalcompany.edit';
//        }

        $return = $this::allowField(true)->save($data,$where);
        $errors = $this::getError();
        return empty( $errors)?['status'=>1,'data'=>$this->toArray()]:['status'=>2,'msg'=> $errors];
    }

    public function getDataById($id){
        $data = $this::where(['id'=>$id])->find();
        return $data;
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

}	