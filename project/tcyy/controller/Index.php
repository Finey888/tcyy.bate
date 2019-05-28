<?php
namespace app\tcyy\controller;
use think\Request;
class Index extends Base
{
    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
    }
    public function index()
    {
        echo returnAjax(['asd'=>11],'dasd',1);exit();
    }
}
