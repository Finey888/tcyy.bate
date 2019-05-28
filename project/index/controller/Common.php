<?php
namespace app\index\controller;
use think\Controller;
use think\auth\Auth;
use think\Request;
use think\Session;
class Common extends Controller
{
    public $menu=[];
    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
        $uid = Session::get("uid");
        if(!$uid){
            $this->redirect('/index/login/index',302);
        }
        $auth = Auth::instance();
        $this->menu = $auth->getMenu(1);
        $controller = strtolower(request()->controller());
        $action = strtolower(request()->action());
        if (!$auth->check($controller.'-'.$action, $uid)) {// 第一个参数是规则名称,第二个参数是用户UID
            if(request()->isAjax() || $action == 'uploadimg'){
                echo json_encode(['status'=>-1,'msg'=>'无权限！']);exit();
            }else{
                echo '无权限！';exit();
            }
        }
        /* $user_id = 1;
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
        }*/
    }
    
}
