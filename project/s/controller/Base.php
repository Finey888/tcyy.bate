<?php
namespace app\s\controller;
use think\Controller;
use think\Request;

class Base extends Controller
{
    public function __construct(Request $request = null,$options = [])
    {
        parent::__construct($request);
    }
}
