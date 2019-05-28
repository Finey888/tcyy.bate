<?php
namespace app\index\controller;
use think\Request;
use think\Session;

class Index extends Common
{
    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
    }
    public function index()
    {
        $username = Session::get("username");
        $this->assign("username",$username);
        return view('Index/index');
    }
    
    //获取菜单
    public function getMenu(){
        return json_encode($this->menu);
    }
    
    public function main(){
        //获取总用户量
        $userModel = new \app\index\model\User();
        $userCount = $userModel->getCount();
        $userNewCount = $userModel->getNewCount();
        $this->assign('userCount',$userCount);
        $this->assign('userNewCount',$userNewCount);
        
        //意见反馈
        $sugestModel = new \app\index\model\Sugest();
        $sugestCount = $sugestModel->getCount(['status'=>['neq',-1]]);
        $this->assign('sugestCount',$sugestCount);
        
        //公司介绍
        $companyModel = new \app\index\model\Company();
        $companyData = $companyModel->getDataById();
        $this->assign('companyData',$companyData);
        
        return view('Index/main');
    }
    
    public function authtest(){
         $user_id = 1;
        // 获取auth实例
        $auth = Auth::instance();
        // 检测权限
        if ($auth->check('show_button', $user_id)) {// 第一个参数是规则名称,第二个参数是用户UID
            echo "{$user_id}号有show_button权限";
        } else {
            echo "{$user_id}号无show_button权限";
        }
         echo '<br/><br/>';
        // 检测权限
         $controller = request()->controller();
        $action = request()->action();
        if ($auth->check($controller.'-'.$action, $user_id)) {// 第一个参数是规则名称,第二个参数是用户UID
            echo "{$user_id}号有".$controller.'-'.$action."权限";
        } else {
            echo "{$user_id}号无".$controller.'-'.$action."权限";
        }
        echo '<br/><br/>';
        $user_id = 2;
        // 检测权限
        if ($auth->check('show_button', $user_id)) {// 第一个参数是规则名称,第二个参数是用户UID
            echo "{$user_id}号有show_button权限";
        } else {
            echo "{$user_id}号无show_button权限";
        }
        echo '<br/><br/>';
        // 检测权限
         $controller = request()->controller();
        $action = request()->action();
        if ($auth->check($controller.'-'.$action, $user_id)) {// 第一个参数是规则名称,第二个参数是用户UID
            echo "{$user_id}号有".$controller.'-'.$action."权限";
        } else {
            echo "{$user_id}号无".$controller.'-'.$action."权限";
        }
    }
}
