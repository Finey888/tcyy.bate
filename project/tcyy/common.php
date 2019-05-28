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
 * @同意返回数据格式
 * @param array $data 需要返回的数据
 * @param string $msg 需要返回的提示
 * @param int $status 状态
 */
function returnAjax($data,$msg,$status=1){
    switch ($status){
        case 1:$status=2001;break;//操作成功
        case 2:$status=5001;break;//统一失败
        case 3:$status=7001;break;//没有权限
        case 4:$status=6001;break;//登录失败
        default:$status=5001;break;
    }
    $return = array(
        'header'=>array(
            'status'=>$status,
            'msg'=>$msg,
            't'=>date('Y-m-d H:i:s',time())
        ),
        'body'=>empty($data)?'':$data
    );
    echo json_encode($return,true);exit();
}
