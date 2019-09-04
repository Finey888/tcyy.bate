<?php
namespace app\tcyy\model;

use think\Db;

class CoursesVideos extends Common {
    //类型转换
    protected $type = [
        'ctime' => 'timestamp:Y-m-d H:i:s'
    ];
    protected $name = 'courses_videos';

    //自定义初始化
    protected function initialize()
    {
        parent::initialize();
    }

	//获取视频列表
    public function getVideoListByCid($param){
        $where = [
            'cid' => $param,
            'isdel' => '0'
        ];
//        $where = ['cid' => $param];
        $data = $this :: where($where) -> field('id,title,prices,contents,episodes,urls,views,ctime,previews') -> order('episodes asc') ->select();
        return $data -> toArray();
    }

    //保存视频信息
    public function saveCourseVideoInfo($data){
       $sv = $this::allowField(true)->save($data);
       $errors = $this::getError();
        return empty( $errors) ? ['status' => 1,'data' => $this->toArray()] : ['status' => 2,'msg' => $errors];
    }

    public function updateVideoById($id)
    {
        return $this :: where(['id'=>$id])->setInc('views',1);
    }

    public function getVieosPageByCondition($page=1,$count=10,$where=[],$sort){
        $data = $this
            ->alias('v')
            ->where($where)
            ->join('tcyy_courses c', ' c.id = v.cid ', 'right')
            ->join('tcyy_user_info u', ' u.id = c.uid ', 'left')
            ->field('c.id AS cid,c.title AS ctitle,c.price,c.gid,v.id AS vid,v.title AS vtitle,DATE_FORMAT(FROM_UNIXTIME(v.ctime),\'%Y-%m-%d\') AS ctimes,v.views,u.nickname,v.previews ')->page($page.','.$count)->order($sort)->select();
        return $data;
    }

    public function deleteVideosByIds($vid)
    {
        $where['id'] = ['eq',$vid];
        return $this :: where($where)->update(['isdel' => 1]);
    }

    public function existsUserVideos($uid,$vid)
    {
        return $this  -> alias('v') -> join('tcyy_courses c', ' c.id = v.cid ', 'inner') -> where(['c.uid'=>$uid,'v.id' => $vid]) -> field('v.id as vid')->count();
    }

    public function deleteVideosByCid($cid){
        $where['cid'] = ['eq',$cid];
        return $this :: where($where)->update(['isdel' => 1]);
    }
}	