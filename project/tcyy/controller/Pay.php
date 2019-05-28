<?php
namespace app\tcyy\controller;
use think\Request;
use think\Loader;
class Pay extends Base
{
    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
    }
   /**
    * @支付接口
    */
   public function wxpay(){
        $data = input('get.');
       /* 
        $this->userData=(object)['id'=>1073];
        $data['type']=1;
        $data['id']=3;
        */
        if($data['type'] == 1){
            //充值VIP
            $return = $this->payVip($data);
            $price = $return['vipData']['price']*100;
            $title = '充值会员';
            $sn = $return['vipData']['out_trade_no'];
        }else{
            //充值课堂
            $return = $this->payCurriculum($data);
            $price = $return['curriculumData']['price']*100;
            $title = '购买课程';
            $sn = $return['curriculumData']['out_trade_no'];
        }
     
        //填写配置参数
         $options = array(
                 'appid' 	=> 	'wxed56e6662b878b3b',		//填写微信分配的公众开放账号ID
                 'mch_id'	=>	'1499388322',				//填写微信支付分配的商户号
                 'notify_url'=>	 'http://'.$_SERVER['HTTP_HOST'].url('Paycallback/wxpay_success'),	//填写微信支付结果回调地址
                 'key'		=>	'4c507c27edee36de579ebf45efa8277a'				//填写  商户支付密钥Key。审核通过后，在微信发送的邮件中查看 Tianchiyueya2016jhkq5miaolei5058   //tianchiyueya88888888888888888888
         );


        //统一下单方法
       // Vendor("Wxapi.WechatAppPay");
        Loader::import('Wxapi.WechatAppPay');
        $wechatAppPay = new \WechatAppPay($options);
        $params['body'] = $title;						//商品描述
        $params['out_trade_no'] = $sn;	//自定义的订单号
        $params['total_fee'] = $price;					//订单金额 只能为整数 单位为分
        $params['trade_type'] = 'APP';					//交易类型 JSAPI | NATIVE | APP | WAP 
        $result = $wechatAppPay->unifiedOrder( $params );
        
        if($result["return_code"] != "SUCCESS"){
            returnAjax(['msg'=>$result['return_msg']], '支付请求失败', 2);
        }
        
        $result['timestamp'] = time();
        
        $sign_array=[];
        $sign_array['appid']     = $result['appid'];    //注意 $sign_array['appid'] 里的参数名必须是appid
        $sign_array['partnerid'] = $result['mch_id'];   //注意 $sign_array['partnerid'] 里的参数名必须是partnerid
        $sign_array['prepayid']  = $result['prepay_id'];//注意 $sign_array['prepayid'] 里的参数名必须是prepayid
        $sign_array['package']   = 'Sign=WXPay';        //注意 $sign_array['package'] 里的参数名必须是package
        $sign_array['noncestr']  = $result['nonce_str'];//注意 $sign_array['noncestr'] 里的参数名必须是noncestr
        $sign_array['timestamp'] =  time();//注意 $sign_array['timestamp'] 里的参数名必须是timestamp
        $sign_array['sign'] = $this->MakeSign($sign_array);
        
        echo json_encode($sign_array);  
      //  $str = '{"appid":"'.$sign_array['appid'].'","noncestr":"'.$sign_array['nonce_str'].'","package":"Sign=WXPay","partnerid":"'.$sign_array['partnerid'].'","prepayid":"'.$result['prepay_id'].'","timestamp":'.$result['timestamp'].',"sign":"'.$sign_array['sign'].'"}';
      //  echo $str;exit();
        //{"appid":"wx0411fa6a39d61297",
        //"noncestr":"aqV0PzEhMLdAMYlZ",
        //"package":"Sign=WXPay",
        //"partnerid":"1230636401",
        //"prepayid":"wx2018030721131040a9e161640249064659",
        //"timestamp":1520428390,
        //"sign":"93FF41C7FDDB686B5AEE34B58E5117C2"}
        //returnAjax($result, '生成订单成功！', 1);
   }
   
   //会员微信支付
   private function payVip($data){
       //获取会员价格
       $vipModel = new \app\tcyy\model\Vip();
       $vipData = $vipModel->getDataFind($data['id']);

        //生成本地账单号
        $payModel = new \app\tcyy\model\Pay();
        $payData = $payModel->getDataFind();
        $sn = 'No'.date('YmdHis',  time()).($payData['id']+1);
       
        $parm = array(
            'datatype'=>1,
            'type'=>1,//1.微信支付
            'title'=>'购买会员',
            'uid'=>$data['userid'],
            'order_sn'=>$sn,
            'appid'=>'wx0ee582c9c218e1c1',//调用接口提交的应用ID
            'mch_id'=>'1409315202',//商户ID
            'body'=>'购买会员',//商品描述
            'out_trade_no'=>$sn,//out_trade_no
            'spbill_create_ip'=>$this->getIP(),// 终端IP
            'createtime'=>time(),//交易其实时间
            'notify_url'=>'回调地址',//商品价格 分
            'trade_type'=>'APP',//商品价格 分
            'paytype'=>1,//1.未支付
            'vip_id'=>$data['id'],
            'price'=>$vipData['price']
        );
        
        //生成订单
        $returnPay = $payModel->saveData($parm);
        if(!$returnPay){
            return false;
        }
        return ['payId'=>$returnPay,'vipData'=>$parm];
   }
   
   //充值课堂
   private function payCurriculum($data){
       //获取课程价格
       $curriculumModel = new \app\tcyy\model\Curriculum();
       $curriculumData = $curriculumModel->getDataById($data['id']);
       
        //生成本地账单号
        $payModel = new \app\tcyy\model\Pay();
        $payData = $payModel->getDataFind();
        $sn = 'No'.date('YmdHis',  time()).($payData['id']+1);
        
        $parm = array(
            'datatype'=>2,//1.会员支付   2.课堂购买
            'type'=>1,//1.微信支付
            'title'=>'购买课堂',
            'uid'=>$data['userid'],
            'order_sn'=>$sn,
            'appid'=>'wx0ee582c9c218e1c1',//调用接口提交的应用ID
            'mch_id'=>'1409315202',//商户ID
            'body'=>'购买会员',//商品描述
            'out_trade_no'=>$sn,//out_trade_no
            'spbill_create_ip'=>$this->getIP(),// 终端IP
            'createtime'=>time(),//交易其实时间
            'notify_url'=>'回调地址',//商品价格 分
            'trade_type'=>'APP',//商品价格 分
            'paytype'=>1,//1.未支付
            'vip_id'=>$data['id'],
            'price'=>$curriculumData['price']
        );
        
        //生成订单
        $returnPay = $payModel->saveData($parm);
        if(!$returnPay){
            return false;
        }
        return ['payId'=>$returnPay,'curriculumData'=>$parm];
   }
   
   private function MakeSign( $params ){
        //签名步骤一：按字典序排序数组参数
        ksort($params);
        $string = $this->ToUrlParams($params);
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=4c507c27edee36de579ebf45efa8277a";
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }
    
    /**
     * 将参数拼接为url: key=value&key=value
     * @param   $params
     * @return  string
     */
    private function ToUrlParams( $params ){
        $string = '';
        if( !empty($params) ){
            $array = array();
            foreach( $params as $key => $value ){
                $array[] = $key.'='.$value;
            }
            $string = implode("&",$array);
        }
        return $string;
    }
    
    private function getIP(){
            global $ip;

            if (getenv("HTTP_CLIENT_IP"))
                    $ip = getenv("HTTP_CLIENT_IP");
            else if(getenv("HTTP_X_FORWARDED_FOR"))
                    $ip = getenv("HTTP_X_FORWARDED_FOR");
            else if(getenv("REMOTE_ADDR"))
                    $ip = getenv("REMOTE_ADDR");
            else 
                    $ip = "Unknow";

            return $ip;
    }
}
