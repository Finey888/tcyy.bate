<?php
namespace app\index\model;


use think\Db;

class PersonalPosition extends Common {


    // 设置数据表（不含前缀）
    protected $name = 'personal_position';

    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->_collection = Db::name($this->name);
    }

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

    //根据主键id查询职位详情
    public function getDataById($id){
        $sqlQuery = 'SELECT DISTINCT pt.address,pt.region,pt.nums,pt.professional,pt.descriptions,CASE pt.nature WHEN 1 THEN \'全职\'  ELSE \'兼职\' END AS nature ,'.
                    'CASE pt.status WHEN 0 THEN \'待审核\' WHEN 1 THEN \'上架\' ELSE \'下架\' END AS status,CASE pt.experience when 1 then \'1年以下\' when 2 then \'1-3年\' when 3 then \'3-5年\' else \'5年以上\' end as experience,pst.dict_name AS positiontype,wg.dict_name AS wages,ed.dict_name AS education,pt.lasttime,pt.creatime'.
                    ' FROM tcyy_personal_position pt  LEFT JOIN tcyy_base_dict pst ON pst.dict_value = pt.positiontype AND pst.dict_code=\'positionType\' LEFT JOIN tcyy_base_dict wg  ON wg.dict_value = pt.wages AND wg.dict_code=\'salaryRange\' LEFT JOIN tcyy_base_dict ed'.
                   ' ON  ed.dict_value = pt.education AND ed.dict_code=\'education\' WHERE pt.isdel= 0 AND pt.id = ?';
        $data = $this -> query($sqlQuery,[$id]);
        return $data;
    }

    //分页数据
    public function getPageData($page=1,$count=10,$where,$sort='pt.creatime desc'){
//        $data = $this :: with('personalCompanyInfo') -> field('id,cid,address,positiontype,region,professional,status,wages,experience,education')->where($where)->page($page.','.$count)->order($sort)->select();
//        $st = ['pt.id','cm.name','cm.email','pt.address','pt.region','pt.professional','pt.status','pt.experience','pst.dict_name'=> 'positiontype','wg.dict_name'=>'wages','ed.dict_name' =>'education'];
//        $data = $this ->_collection
//            ->join('tcyy_personal_company cm', ' cm.id = pt.cid', 'inner')
//            ->alias('pt')
//            ->where($where)
//            ->join('tcyy_base_dict pst', ' pst.dict_value = pt.positiontype  ' and 'pst.dict_code='.'\'positionType\'', 'left')
//            ->join('tcyy_base_dict wg', ' wg.dict_value = pt.wages  ' and 'wg.dict_code='.'\'salaryRange\'', 'left')
//            ->join('tcyy_base_dict ed', ' ed.dict_value = pt.education ' and 'ed.dict_code='.'\'education\'', 'left')
//            ->field($st)
//            ->distinct(true)
//            ->page($page.','.$count)->order($sort)->select();
        $page = ($page - 1) * $count;
       $sql =  'SELECT DISTINCT pt.id,cm.name,cm.email,pt.address,pt.region,pt.professional,pt.status,pt.experience,pst.dict_name AS positiontype,wg.dict_name AS wages,ed.dict_name AS education'.
        ' FROM tcyy_personal_company cm  , tcyy_personal_position pt '.
        ' LEFT JOIN tcyy_base_dict pst ON pst.dict_value = pt.positiontype AND pst.dict_code=\'positionType\' '.
        ' LEFT JOIN tcyy_base_dict wg  ON wg.dict_value = pt.wages AND wg.dict_code=\'salaryRange\' '.
        ' LEFT JOIN tcyy_base_dict ed  ON  ed.dict_value = pt.education AND ed.dict_code=\'education\' '.
        ' WHERE pt.cid = cm.id and '.$where .' order by '.$sort .' limit '. $page .','.$count;
         $data = $this -> query($sql);
        return $data;
    }

    //获取总条数
    public function getCount($where=[]){
//        return $this->where($where)->count();
//        return $data = $this :: with('personalCompanyInfo') -> field('id,cid,address,positiontype,region,professional,status,wages,experience,education')->where($where)->count();
        return  $this ->_collection
            ->alias('pt')
            ->where($where)
            ->join('tcyy_personal_company cm', 'cm.id = pt.cid', 'inner')
            ->field('pt.id,cm.name,cm.email,pt.address,pt.positiontype,pt.region,pt.professional,pt.status,pt.wages,pt.experience,pt.education ') -> count();
    }


    //审核数据
    public function  auditPositionById($id){
        return $this -> _collection ->update(['id'=>$id,'status'=>1]);
    }
}	