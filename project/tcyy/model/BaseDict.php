<?php
namespace app\tcyy\model;

class BaseDict extends Common {

    //id单个查询
    public function info($request){
        $id = $request->param('id');		
        return $this->where('id', $id)->find();
    }

    //列表
    public function lists(){
        return $this::field('dict_code,dict_name,dict_value,remark')->order('dict_sort asc')->select();
    }

}	