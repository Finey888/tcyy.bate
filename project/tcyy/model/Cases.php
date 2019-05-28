<?php
namespace app\tcyy\model;
use think\Db;
class Cases extends Common
{
    protected $type = [
        'creatime'    =>  'timestamp:Y-m-d H:i:s',
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
    
    public function CasesFollow()
    {
        return $this->hasOne('CasesFollow','touid','uid');
    }
    
    public function CasesImage()
    {
        return $this->hasMany('CasesImage','case_id');
    }
    
    public function getDataById($id){
        return $this::where(['id'=>$id])->find();
    }
    
    //获取数据
    public function getDataByPage($uid,$group_id,$type,$page=1,$count=10){

        /**
         * $return = $this::with(['Manual'=>function($query)use ($manualWhere){
                    $query->where($manualWhere)->field('id,title,group')->order('sort desc,id desc');
                }])->where($where)->select();
         */
        
        $where=[];
        if(($type==1)){
            $where = ['group_id'=>$group_id,'status'=>1,'type'=>$type];
        }elseif(($type==2)){
            $where = ['status'=>1,'type'=>$type,'uid'=>$uid];
        }elseif(($type==3)){
            $where = ['status'=>1,'type'=>1,'uid'=>$uid];
        }
        
        $data = $this::with(['UserInfo'=>function($query){
                    $query->field('id,uid,nickname,headurl');
                },'CasesFollow'=>function($query)use($uid){
                    $query->where(['uid'=>$uid])->field('id,touid,status');
                },'CasesImage'=>function($query){
                    $query->field('id,case_id,image');
                }])
                ->where($where)
                ->page($page.','.$count)
                ->order('creatime desc')
                ->select();
				
        return empty($data)?[]:$data->toArray();
    }
    
    public function getDataByIdss($id,$uid){
        $where=['id'=>$id,'status'=>1];
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
    
    public function getDataByIDR($id,$uid){

        $where=[];
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
    
    //发布病例
    public function saveData($data){
        $return = $this::validate('Cases.add')->allowField(true)->save($data);
        return empty($this::getError())?['status'=>1,'id'=>$this->id]:['status'=>2,'msg'=>$this::getError()];
    }
    
    //新增点占
    public function saveThing($id){
            $this::where(['id'=>$id])->setInc('thing');
    }

    //新增阅读量
    public function saveRead($id){
            $this::where(['id'=>$id])->setInc('read');
    }
    
    //新增评论
    public function saveComment($id){
            $this::where(['id'=>$id])->setInc('comments');
    }
    
    //减评论数
    public function saveJComment($id){
            $this::where(['id'=>$id])->setDec('comments');
    }
    
    //发布草稿
    public function fbcg($case_id){
        return $this::save(['creatime'=>time(),'type'=>1],['id'=>$case_id]);
    }
    
    //删除草稿
    public function delDraft($case_id){
        return $this::save(['status'=>-1],['id'=>$case_id]);
    }
    //删除
    public function del($id){
        $this::delete($id);
    }
    
    //获取图片数据
    public function getImageAttr($value){
        if(stripos($value,"http://") == false){
            return $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$value;
        }else{
            return str_replace($_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'], '', $value);
        }
    }
    
    //添加时间
    public function setCreatimeAttr(){
        return time();
    }
    
    //获取图片数据
    public function setImageAttr($value){
        if(stripos($value,"http://") == false){
            return str_replace($_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'], '', $value);
        }else{
            return $value;
        }
    }
    
    public function setContentsAttr($value){
        return htmlspecialchars($value,ENT_QUOTES);
    }

    public function getContentsAttr($value){
        return htmlspecialchars_decode($value,ENT_QUOTES);
    }
}
