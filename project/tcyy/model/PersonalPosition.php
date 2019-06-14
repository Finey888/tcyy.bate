<?php
namespace app\tcyy\model;

use think\Db;

class PersonalPosition extends Common {

    protected $name = 'personal_position';

    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->_collection = Db::name($this->name);
    }

    //根据主键查询职位信息
    public function getDataById($id){
        $data = $this::where(['id'=>$id])->find();
        return $data;
    }

    //保存职位信息
    public function saveData($data,$where=[]){
        $return = $this::allowField(true)->save($data,$where);
        $errors = $this::getError();
        return empty( $errors)?['status'=>1,'data'=>$this->toArray()]:['status'=>2,'msg'=> $errors];
    }

    //分页查询职位信息
    public function getPageData($page=1,$count=10,$where=[],$sort='pt.lasttime desc'){
        $data = $this ->_collection
            ->alias('pt')
            ->where($where)
            ->join('tcyy_personal_company cm', ' cm.id = pt.cid', 'inner')
            ->field('pt.id,cm.name,cm.email,pt.address,pt.positiontype,pt.region,pt.professional,pt.status,pt.wages,pt.experience,pt.education ')->page($page.','.$count)->order($sort)->select();
        return $data;
    }

    //逻辑删除
    public function  delPostionById($id){
        $data = $this::where(['id' => $id])->update(['isdel' => 1]);
        return $data;
    }
}	