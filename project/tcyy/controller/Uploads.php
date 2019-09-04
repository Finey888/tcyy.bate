<?php
namespace app\tcyy\controller;
use think\Request;
class Uploads extends Common
{
    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
    }
    
    /**
     * @上传文件
     * @param int $type 1.项目图片
     * @param int $filetype 1.图片  2.视频
     */
    public function Files(){
        $get = input('post.');
        if(empty($get['type'])){returnAjax([],'缺少参数1',2);}
        if(empty($get['filetype'])){returnAjax([],'缺少参数2',2);}
    
        $files=request()->file();
       
        switch ($get['type']){
            case 1:
                //项目图片上传
                $this->projectImg($files, $get['filetype']);
                break;
            case 2:
                //头像
                $this->userHeader($files);
                break;
            case 3:
                //病例秀
                $this->cases($files,$get['filetype']);
                break;
            case 4:
                //项目用户头像
                $this->projectUserHeader($files);
                break;
            case 5:
                //在线课堂
                $this->curriculum($files,$get['filetype']);
                break;
            case 6:
                //用户自定义图片
                $this->picture($files);
                break;
            case 7:
                //招聘
                $this->recruit($files);
                break;
        }
        
    }
    
    /**
     * @项目图片上传
     */
    private function projectImg($tmp_name,$filetype){
        $returnImg=[];
        if($filetype == 1){
            $filename = 'images';
        }elseif($filetype == 2){
            $filename = 'videos';
        }else{
            $filename = 'audios';
        }
  
        foreach($tmp_name as $tv){
           
            $return = uploadImg($tv,'/Project/'.$filename.'/');
           
            $data = json_decode($return,true);
             
            if($data['status'] == -1){
                returnAjax([],$data['msg'],2);
            }else{
                $returnImg[]=['saveUrl'=>$data['data'],'showUrl'=>$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$data['data']];
            }
        }
        returnAjax($returnImg,'上传成功！',1);
    }
    
    /**
     * @上传头像
     */
    private function userHeader($tmp_name){
        $returnImg=[];
        foreach($tmp_name as $tk=>$tv){
            $return = uploadImg($tv,'/header/'.$this->userData->id.'/');
            $data = json_decode($return,true);
            if($data['status'] == -1){
                returnAjax([],$data['msg'],2);
            }else{
                $returnImg[]=['saveUrl'=>$data['data'],'showUrl'=>$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$data['data']];
            }
        }
        returnAjax($returnImg,'上传成功！',1);
    }
    
    /**
     * @上传病例秀
     */
    private function cases($tmp_name,$filetype){
        $returnImg=[];
        if($filetype == 1){
            $filename = 'images';
        }elseif($filetype == 2){
            $filename = 'videos';
        }
        foreach($tmp_name as $tk=>$tv){
            $return = uploadImg($tv,'/cases/'.$this->userData->id.'/'.$filename.'/',$filename);
            $data = json_decode($return,true);
            if($data['status'] == -1){
                returnAjax([],$data['msg'],2);
            }else{
                $returnImg[]=['saveUrl'=>$data['data'],'showUrl'=>$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$data['data']];
            }
        }
        returnAjax($returnImg,'上传成功！',1);
    }
    
    /**
     * @上传项目用户头像
     */
    private function projectUserHeader($tmp_name){
        $returnImg=[];
        foreach($tmp_name as $tk=>$tv){
            $return = uploadImg($tv,'/projectUserHeader/'.$this->userData->id.'/');
            $data = json_decode($return,true);
            if($data['status'] == -1){
                returnAjax([],$data['msg'],2);
            }else{
                $returnImg[]=['saveUrl'=>$data['data'],'showUrl'=>$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$data['data']];
            }
        }
        returnAjax($returnImg,'上传成功！',1);
    }
    
    /**
     * @课堂图片
     */
    private function curriculum($tmp_name,$filetype){
        $returnImg=[];
        if($filetype == 1){
            $filename = 'images';
        }elseif($filetype == 2){
            $filename = 'videos';
        }
        foreach($tmp_name as $tk=>$tv){
            $return = uploadImg($tv,'/curriculum/'.$filename.'/',$filename);
            $data = json_decode($return,true);
            
            if($data['status'] == -1){
                returnAjax([],$data['msg'],2);
            }else{
                $returnImg[]=['saveUrl'=>$data['data'],'showUrl'=>$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$data['data']];
            }
        }
        returnAjax($returnImg,'上传成功！',1);
    }
    
    /**
     * @用户自定义图片
     */
    private function picture($tmp_name){
        $returnImg=[];
        $filename = 'images';
        foreach($tmp_name as $tk=>$tv){
            $return = uploadImg($tv,'/picture/'.$filename.'/');
            $data = json_decode($return,true);
            
            if($data['status'] == -1){
                returnAjax([],$data['msg'],2);
            }else{
                $returnImg[]=['saveUrl'=>$data['data'],'showUrl'=>$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$data['data']];
            }
        }
        returnAjax($returnImg,'上传成功！',1);
    }

    /**
     * @招聘图片
     */
    private function recruit($tmp_name){
        $returnImg=[];
        $filename = 'images';
        foreach($tmp_name as $tk=>$tv){
            $return = uploadImg($tv,'/recruit/'.$filename.'/');
            $data = json_decode($return,true);

            if($data['status'] == -1){
                returnAjax([],$data['msg'],2);
            }else{
                $returnImg[]=['saveUrl'=>$data['data'],'showUrl'=>$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$data['data']];
            }
        }
        returnAjax($returnImg,'上传成功！',1);
    }
}
