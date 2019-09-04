<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/**
* 上传图片
* @param $file  文件
* @param string $name 保存的文件名 默认自动生成，
* @param string $path 保存路径，
* @return mixed  返回对应错误数据
*/
function uploadImg($file,$path,$name=''){
   // 移动到框架应用根目录/public/uploads/ 目录下
   $movepath = strtr($path,"/","\\");
   $movepath = substr($movepath,0,strlen($movepath)-1);
   $fileExt = $file -> getExtension();
    if($name == 'videos' && $fileExt != "mp4"){
        return json_encode(['status'=>-1,'msg'=>'视频文件非mp4格式']);
    }
   $info = $file->move(ROOT_PATH . 'public' . $movepath);
   if($info){
       $fileurl = $path.strtr($info->getSaveName(),"\\","/");
       return json_encode(['status'=>1,'data'=>$fileurl]);
   }else{
       // 上传失败获取错误信息
       return json_encode(['status'=>-1,'msg'=>$file->getError()]);
   }
}
/**
 * @积分类型
 * type  1.签到  2.发布并列秀 3.点赞 4.积分商城 5.激活邀请码
 */
function getPointType($type){
    $str='';
    switch ($type){
        case 1:$str='签到';break;
        case 2:$str='每日首次发布病例';break;
        case 3:$str='点赞';break;
        case 4:$str='积分商城兑换';break;
        case 5:$str='激活邀请码';break;
    }
}