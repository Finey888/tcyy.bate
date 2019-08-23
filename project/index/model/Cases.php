<?php
namespace app\index\model;

class Cases extends Common{

    //类型转换
    protected $type = [
        'creatime' => 'timestamp:Y-m-d H:i:s'
    ];
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }

    public function UserInfo()
    {
        return $this->hasOne('UserInfo','uid','uid');
    }

    public function CasesImage()
    {
        return $this->hasMany('CasesImage','case_id');
    }

    public function getDataById($id){
        return $this::where(['id'=>$id])->find();
    }

    //获取数据
    public function getDataByPage($where=[],$page=1,$count=10){

//        $where=[];
//        if(($type==1)){
//            $where = ['group_id'=>$group_id,'status'=>1,'type'=>$type];
//        }elseif(($type==2)){
//            $where = ['status'=>1,'type'=>$type,'uid'=>$uid];
//        }elseif(($type==3)){
//            $where = ['status'=>1,'type'=>1,'uid'=>$uid];
//        }
//
        $data = $this::with(['UserInfo'=>function($query){
            $query->field('id,uid,nickname,headurl');
        }])
            ->where($where)
            ->page($page.','.$count)
            ->order('creatime desc')
            ->select();

        return empty($data)?[]:$data->toArray();
//        $data = $this::join('tcyy_user_info u', ' u.uid = tcyy_cases.uid ', 'left') ->where($where)
//            -> page($page.','.$count)
//            -> field('tcyy_cases.id,thing,read,comments,DATE_FORMAT(FROM_UNIXTIME(creatime),\'%Y-%m-%d\') as creatime,u.nickname')
//            -> order('creatime desc')
//            -> select();
//
//        return empty($data)?[]:$data->toArray();
    }

    public function getCount($where=[]){
        return $data = $this::join('tcyy_user_info u', ' u.uid = tcyy_cases.uid ', 'left') ->where($where)
            -> field('tcyy_cases.id,thing,read,comments,creatime,u.nickname') ->count();
    }

    public function getCasesDetails($id){
        $where=['id'=>$id,'status'=>1];
        $data = $this::with(['UserInfo'=>function($query){
            $query->field('id,uid,nickname,headurl');
        },'CasesImage'=>function($query){
            $query->field('id,case_id,image');
        }])
            ->where($where)
            ->find();

        return empty($data)?[]:$data->toArray();
    }

    public function getDataByIDR($id,$uid){

        $where = ['id'=>$id,'uid'=>$uid,'status'=>1];

        $data = $this::with(['UserInfo'=>function($query){
            $query->field('id,uid,nickname,headurl');
        },'CasesFollow'=>function($query)use($uid){
            $query->where(['uid'=>$uid])->field('id,touid,status');
        },'CasesImage'=>function($query){
            $query->field('id,case_id,image');
        }])
            ->where($where)
            ->find();

        return empty($data)?[]:$data->toArray();
    }


}