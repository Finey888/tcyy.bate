<?php
namespace app\tcyy\controller;
use think\Request;
class Paycallback extends Base
{
    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
    }
    
    //课堂回调
   public function wxpay_success(){
      //获取通知的数据
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $data = array();
        if( empty($xml) ){
                return false;
        }
        
        if(!$xml){
                return false;
        }
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);		
		
        if( !empty($data['return_code']) ){
                if( $data['return_code'] == 'FAIL' ){
                        return false;
                }
        }
       
	    $payModel = new \app\tcyy\model\Pay();
        $datauser = $payModel->getDataByTrade($data['out_trade_no']);
        
        if($datauser['paytype'] != 1){
            $datareturn['return_code'] = 'SUCCESS';
            $datareturn['return_msg'] = 'OK';
            echo $this->data_to_xml( $data );
            die();
        }
        
        if($datauser['datatype'] == 1){
            //充值会员
            $this->rechargeVip($datauser['uid'], $datauser['vip_id']);
        }else{
            //充值课堂
            $this->curriculum($datauser);
        }
        
        //修改充值记录
        $payModel->updateData(['paytype'=>2,'paytime'=> time()], ['id'=>$datauser['id']]);
        
        //return "<xml> <return_code><![CDATA[SUCCESS]]></return_code> <return_msg><![CDATA[OK]]></return_msg> </xml>";
        $datareturn['return_code'] = 'SUCCESS';
        $datareturn['return_msg'] = 'OK';
        echo $this->data_to_xml( $data );
        die();
		
   }
   
   /**
    * 输出xml字符
    * @param	$params		参数名称
    * return	string		返回组装的xml
    **/
   public function data_to_xml( $params ){
           if(!is_array($params)|| count($params) <= 0)
           {
               return false;
           }
           $xml = "<xml>";
           foreach ($params as $key=>$val)
           {
                   if (is_numeric($val)){
                           $xml.="<".$key.">".$val."</".$key.">";
                   }else{
                           $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
                   }
           }
           $xml.="</xml>";
           return $xml; 
   }
   
   //充值会员回调
   /*
    * uid 用户ID
    * id 会员价格ID
    */
   private function rechargeVip($uid,$id){
       $vipModel = new \app\tcyy\model\Vip();
       $vipData = $vipModel->getDataFind($id);
      
       $userModel = new \app\tcyy\model\User();
       $user = $userModel->getDataById($uid);
             
       if($user['vip_end_date']< time()){
           $endTime = strtotime('+'.$vipData['mouth'].' month',time());
       }else{
           $endTime = strtotime('+'.$vipData['mouth'].' month',$user['vip_end_date']);
       }
       $userModel->saveVip($uid, $endTime);
       
        //插入消息库
        $mesModel = new \app\tcyy\model\Message();
        $mesModel->addData('恭喜开通会员成功', 1, $uid,'','成功会员充值 '.$vipData['mouth'].'个月,到期时间：'.date('Y-m-d',$endTime),'');
   }
   
   /**
    * 购买课程
    * @payData 支付成功通知,支付实体信息
    */
   private function curriculum($payData){
       $coursesUserModel = new \app\tcyy\model\CoursesUser();
       
       $coursesModel = new \app\tcyy\model\Courses();

       $data['cid'] = $payData['vip_id'];
       $data['uid'] = $payData['uid'];
       $data['multiinfo'] = $payData['body'];
       $data['amounts'] = $payData['price'];
       $data['btimes'] = strtotime(date('Y-m-d H-i-s',time()));

       $coursesUserModel->saveCoureseByUserPaid($data);

       $data = $coursesModel -> getInfoById($data['cid']);

        //插入消息库
        $mesModel = new \app\tcyy\model\Message();
        $mesModel->addData('恭喜购买课程成功', 1, $data['uid'],'','成功购买课程《' . $data['title'] . ‘》’ .$payData['body'] . ' 集视频','');
   }
   
}
