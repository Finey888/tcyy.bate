<?php
namespace app\index\model;
use think\Db;
class BrandProduct extends Common
{
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    public function ExtendGroup(){
        return $this->hasOne('ExtendGroup','id','type');
    }
    
    public function BrandProductClass(){
        return $this->hasOne('BrandProductClass','id','product_class');
    }
    
    //获取总条数
    public function getCount($where=['status'=>['neq',-1]]){
        $data = $this::with(
                [
                    'BrandProductClass'=>function($query){$query->withField("id,title");},
                    'ExtendGroup'=>function($query){$query->withField("id,title");}
                ]
                )->field('id,status,title,sort,purls,product_class,type')->where($where)->count();
        return $data;
    }
    
    public function getPageData($page=1,$count=10,$where=['status'=>['neq',-1]],$sort='id desc'){
        
        $data = $this::with(
                [
                    'BrandProductClass'=>function($query){$query->withField("id,title");},
                    'ExtendGroup'=>function($query){$query->withField("id,title");}
                ]
                )->field('id,status,title,sort,purls,product_class,type')->where($where)->page($page.','.$count)->order($sort)->select();
        return $data;
    }
    
    /**
     * @获取所有可用一级分类数据
     */
    public function getAllData(){
        $data = $this::where(['status'=>1,'pid'=>0])->field('id,title')->order('sort desc,id desc')->select();
        return $data;
    }
    
    /**
     * @保存数据
     */
    public function saveData($data,$where=[]){
        $saveType = 'BrandProduct.add';
        
        if(!empty($where)){$saveType = 'BrandProduct.edit';}
        
        $return = $this::validate($saveType)->allowField(true)->save($data,$where);
        
        return $this::getError();
    }
    
    //根据ID获取数据
    public function getDataById($id){
        return $this::where(['id'=>$id])->find();
    }
    
    //获取列表数据
    public function getListData($where=['status'=>['neq',-1]]){
        $data = $this::where($where)->field('id,title,image,status,sort,info,class,type')->select();
        return $data->toArray();
    }
    
    //修改状态
    public function setStatus($id,$status){
        $data = $this->where(['id'=>$id])->update(['status'=>$status]);
        return $data;
    }
    
    //根据ID删除数据删除
    public function delById($id){
        return $this::update(['id'=>$id,'status'=>-1]);
    }


    //设置字段值
    public function setContentsAttr($value){
        return htmlspecialchars($value,ENT_QUOTES);
    }
    
    public function getContentsAttr($value){
        return htmlspecialchars_decode($value,ENT_QUOTES);
    }
    
}
