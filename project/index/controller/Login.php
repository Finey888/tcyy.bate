<?php
/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2018/1/21 0021
 * Time: 14:15
 */

namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Session;

class Login extends Controller
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
    }


    /**
     * 登陆页面
     * @return \think\response\View
     */
    public function index()
    {
        return view('Login/index');
    }

    /**
     * 验证码图片
     * @return \think\Response
     */
    public function verifyImg()
    {
        $captcha = new \think\captcha\Captcha();
        $captcha->fontSize = 30;
        $captcha->length = 4;
        $captcha->useNoise = false;
        return $captcha->entry();
    }

    /**
     * 登陆验证接口
     * @return \think\response\Json
     */
    public function loginVerify()
    {
        $postdata = Request::instance()->param();
        $loginuser = $postdata['loginuser']; //用户名
        $loginpw = $postdata['loginpw']; //登陆密码
        $loginvif = $postdata['loginvif']; //验证码

        if (!$loginuser || !$loginpw || !$loginvif) {
            return json($this->_getResponse("ILLEGAL_REQUEST")); //参数错误
        }
        //验证码验证
        $captcha = new \think\captcha\Captcha();
        if (!$captcha->check($loginvif)) {
            return json($this->_getResponse("VERIFY_ERROR")); //验证码错误
        }
        //用户名验证
        $memberModel = new \app\index\model\Member();
        $userdata = $memberModel->getMemberMsg($loginuser);
        if (!$userdata) {
            return json($this->_getResponse("USER_ERROR")); //用户不存在
        }
        if ($userdata['status'] != 1) {
            return json($this->_getResponse("STATUS_ERROR")); //账号被禁用
        }

        //密码验证
        $password = md5(config('password_str')[0] . $loginpw);
        if ($password != $userdata['password']) {
            return json($this->_getResponse("PASSWORD_ERROR")); //密码错误
        }

        Session::set("username", $userdata['username']);
        Session::set("uid", $userdata['id']);

        return json($this->_getResponse("SUCCESS")); //登陆成功
    }

    /**
     *退出登陆
     * @return \think\response\Json
     */
    public function loginOut()
    {
        Session::clear();
        return json($this->_getResponse("SUCCESS"));
    }

    /**
     * @param $key
     * @param array $data
     * @return mixed
     */
    protected function _getResponse($key, $data = [])
    {
        $maps = [
            "SUCCESS" => ["status" => 0, "msg" => "操作成功", "data" => $data],
            "ILLEGAL_REQUEST" => ["status" => 1, "msg" => "参数错误", "data" => $data],
            "USER_ERROR" => ["status" => 2, "msg" => "用户名不存在", "data" => $data],
            "VERIFY_ERROR" => ["status" => 3, "msg" => "验证码错误", "data" => $data],
            "PASSWORD_ERROR" => ["status" => 4, "msg" => "密码错误", "data" => $data],
            "STATUS_ERROR" => ["status" => 5, "msg" => "该账号已被禁用，请联系管理员", "data" => $data],
        ];
        return $maps[$key];
    }
}