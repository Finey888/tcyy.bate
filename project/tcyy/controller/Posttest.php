<?php
namespace app\tcyy\controller;
use think\Request;
use think\Loader;
class Posttest extends Base
{
    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
    }
    public function index()
    {
        //otv6MZfEBMP6bmWgg2KaePUVhRmjVE30Dhd1ud77bPU=
        $url = "m.tianchiyueya.com:1888/tcyy/Pay/wxpay";
          
        $post_data = array (
            "code" => "1200b0dffdc8a881c310652baf79f8bc",
             "id" => "1",
           
            );
        
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // post数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // post的变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));

        $output = curl_exec($ch);
        curl_close($ch);

        //打印获得的数据
        echo $output;
    }
    
    
    public function uploadtest(){
        return view('index');
    }
    
   
    public function testhtml(){
       /* $content='<img src="/styles/images/default.jpg!c550x260.jpg"/><img src="https://img.xxx.com/images/219_Ig5eZI.jpg!c550x260.jpg" style="max-width:100%"></p>  ';
$pregRule = "/<[img|IMG].*?src=[\'|\"]\/(.*?(?:[\.jpg|\.jpeg|\.png|\.gif|\.bmp]))[\'|\"].*?[\/]?>/";  
$content = preg_replace($pregRule, '<img src="-----------${1}" style="max-width:100%">', $content);  
dump($content);  
exit();
        $content = '<a href="#"><img class="center" src="/styles/images/default.jpg">打算<img class="center" src="/images/219_Ig5eZI.jpg"></a>'
.'<p></p>';
        $dd = preg_replace('/(<img).+(src=\"?.+)\/(.+\.(jpg|gif|bmp|bnp|png)\"?).+>/i',"\${1} \${2}uc/2313images/\${3}>",$content);
        dump($dd);
        exit();
        
        $suffix='------------------';
        $content = '<a href="#"><img class="center" src="https://xxx.com/styles/images/default.jpg"></a>'
.'<p><img class="center" src="https://img.xxx.com/images/219_Ig5eZI.jpg" style="max-width: 100%;"></p>';
        dump($content);
        $pregRule = "/<[img|IMG].*?src=[\'|\"](.*?(?:[\.jpg|\.jpeg|\.png|\.gif|\.bmp]))[\'|\"].*?[\/]?>/";
$content = preg_replace($pregRule, '<img src="${1}'.$suffix.'" style="max-width:100%">', $content);\

dump($content);exit();*/
        $str = '<img src="/Public/plug/editor/attached/image/20150806/20150806105206_92674.png" dasd as /> <img src="https://ss0.bdstatic.com/94oJfD_bAAcT8t7mm9GUKT-xh_/timg?image&quality=100&size=b4000_4000&sec=1520249558&di=f8bd612de61e219d7272409d8397ec12&src=http://imgsrc.baidu.com/imgad/pic/item/32fa828ba61ea8d3d8d6c33f9c0a304e251f5810.jpg"/>';
        //echo $str;
        //dump('<img src="/ueditor/php/upload/image/20180127/1517038946104550.png" alt="1517038946104550.png" width="262" height="241"/>');
        dump($str);
        $hostStr = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'];
            //$str=preg_replace('/(<img.+src=?.+)(\"\/)(.+\.\"?.+>)/i',"<img src=\"{$hostStr}/\${3}",$str);
   //     preg_replace($pregRule, '${1} style="max-width:100%">', $content);  
            dump(preg_replace('/(<[img|IMG].*?src=[\'|\"])(\/)(.*?(?:[\.jpg|\.jpeg|\.png|\.gif|\.bmp]))[\'|\"].*?[\/]?/','${1}'.$hostStr.'${2}${3}"',$str));
         /*   dump(preg_replace('/(<img.+src=?.+)(\"\/)(.+\.\"?.+>)/i',"/\${2}",$str));
            dump(preg_replace('/(<img.+src=?.+)(\"\/)(.+\.\"?.+>)/i',"/\${3}",$str));
            dump($str);*/
    }


    public function ts(){
         //加载第三方库
        Loader::import('miPush.android_example');
        $android_sms = new \android_example();
        $data['title'] = '测试推送';
        $data['desc'] = '今天2018-02-07';
        $data['payload'] = '{"test":1,"sss":"It\'s a string"}';
        dump($android_sms->send($data,['otv6MZfEBMP6bmWgg2KaePUVhRmjVE30Dhd1ud77bPU=']));
        
        
       /* Loader::import('miPush.ios_example');
        $ios_sms = new \ios_example();
        $data2['desc'] = '这是一条推送消息';
        $data2['payload'] = '{"test":1,"sss":"It\'s a string"}';
        dump($ios_sms->send($data2));*/
       
    }
}
