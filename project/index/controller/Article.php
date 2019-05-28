<?php
namespace project\index\controller;
use think\Request;
class Article extends Common
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
    }
    
    public function index(){
        return view('Article/index');
    }
}
