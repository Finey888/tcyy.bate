<?php
namespace app\tcyy\model;
use think\Db;
class UserPicture extends Common
{
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }
    
    /**
     * @获取详情
     * type  1.签到  2.发布并列秀 3.点赞 4.积分商城 5.激活邀请码 6.有人激活邀请码
     */
    public function addData($pram,$uid){
        $data = $this::save(['name'=>$pram['name'],'uid'=>$uid,'fid'=>$pram['fid'],'image'=>$pram['image'],'status'=>1]);
        return empty($data)?[]:$this->data['id'];
    }
    
    //获取数据
    public function getDataByPage($fid,$page=1,$count=10){
        $where = ['fid'=>$fid,'status'=>1];
        $data = $this::where($where)
                ->page($page.','.$count)
                ->field('id,image,name as title')
                ->order('id desc')
                ->select();
        return empty($data)?[]:$data->toArray();
    }
    
    //删除
    public function del($id,$uid){
        $data = $this::allowField(true)->save(['status'=>-1],['uid'=>$uid,'id'=>$id]);
        return $data;
    }
    
    public function getImageAttr($value)
    {
        return $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$value;
    }
    
    
}
