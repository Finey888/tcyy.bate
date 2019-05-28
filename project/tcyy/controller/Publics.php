<?php
namespace app\tcyy\controller;
use think\Controller;
use think\Request;
class Publics extends Controller
{
    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
    }
    public function advert()
    {
        $advertModer = new \app\tcyy\model\Advert();
        $data = $advertModer->selectData();
        if(empty($data)){
            $return=[];
        }
        
        $return = collection($data)->toArray();
        
        $colorModer = new \app\tcyy\model\AdvertColor();
        $dataColor = $colorModer->selectData();
        $color = $dataColor->toArray();
        
        returnAjax(['data'=>$return,'color'=>$color['color']],'',1);exit();
    }
    
    public function advertUpdateTime(){
        $advertModer = new \app\tcyy\model\AdvertTime();
        $data = $advertModer->selectData();
        $times = $data->toArray();
        returnAjax($times,'',1);exit();
    }
    
    public function version(){
        
    }
    
}
