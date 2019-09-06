<?php
namespace app\index\model;

class Courses extends Common {
    //类型转换
    protected $type = [
        'creattime' => 'timestamp:Y-m-d H:i:s'
    ];
    //自定义初始化
    protected function initialize()
    {
        parent::initialize();
    }

	//分页查询获取课程分页列表数据
    public function getCoursePageListByCondition($page,$count,$where){
        $data = $this -> alias("cs")
                      -> join("tcyy_user_info u","u.uid=cs.uid",'inner')
                      -> join('tcyy_group g','cs.gid=g.id','inner')
                      -> where($where)
                      -> field('cs.id,cs.title,cs.contents,u.nickname,cs.ctype,g.title as gtitle,cs.price,cs.creattime')
                      -> page($page.','.$count)
                      -> order('creattime desc')
                      -> select();
        return empty($data)?[]:$data->toArray();
    }

    //课程信息统计
    public function getCoursesCount($where){
        return $this -> where($where) -> count();
    }

    //获取课程详情
    public function getCourseInfoById($id){
        return $this->where(['id'=>$id])->find();
    }

    public function getCoursesOrdersList($page,$count,$where){
        $data = $this -> alias("c")
            -> join('tcyy_courses_user cu','c.id = cu.cid')
            -> where($where)
            -> field('cu.id,c.title,u.nickname as publisher,DATE_FORMAT(FROM_UNIXTIME(cu.btimes),\'%Y-%m-%d %h:%i:%s\') as btimes,cu.cid,cu.multiinfo,cu.amounts,u1.nickname as buyer,cu.withdraw')
            -> join("tcyy_user_info u","u.uid=c.uid")
            -> join("tcyy_user_info u1","u1.uid=cu.uid")
            -> page($page.','.$count)
            -> order('cu.btimes desc')
            -> select();

        return empty($data)?[]:$data->toArray();
    }

    public function getOrdersCount($where){
        return  $this -> alias("c")
            -> join('tcyy_courses_user cu','c.id = cu.cid','inner')
            -> where($where)
            -> count();
    }
}	