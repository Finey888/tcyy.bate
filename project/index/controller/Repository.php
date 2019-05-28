<?php
/**
 * Created by IntelliJ IDEA.
 * User: Linda Rong (Rainbow)
 * Date: 2018/1/22 0022
 * Time: 18:42
 * *********************************************
 *                 _ooOoo_
 *                o8888888o
 *                88" . "88
 *                (| -_- |)
 *                O\  =  /O
 *             ____/`---'\____
 *           .'  \\|     |//  `.
 *          /  \\|||  :  |||//  \
 *         /  _||||| -:- |||||-  \
 *         |   | \\\  -  /// |   |
 *         | \_|  ''\---/''  |   |
 *         \  .-\__  `-`  ___/-. /
 *       ___`. .'  /--.--\  `. . __
 *    ."" '<  `.___\_<|>_/___.'  >'"".
 *   | | :  `- \`.;`\ _ /`;.`/ - ` : | |
 *   \  \ `-.   \_ __\ /__ _/   .-` /  /
 *====`-.____`-.___\_____/___.-`____.-'======
 *                 `=---='
 *^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
 *         佛祖保佑       永无BUG
 *    佛曰:
 *      写字楼里写字间，写字间里程序员；
 *      程序人员写程序，又拿程序换酒钱。
 *      酒醒只在网上坐，酒醉还来网下眠；
 *      酒醉酒醒日复日，网上网下年复年。
 *      但愿老死电脑间，不愿鞠躬老板前；
 *      奔驰宝马贵者趣，公交自行程序员。
 *      别人笑我忒疯癫，我笑自己命太贱；
 *      不见满街漂亮妹，哪个归得程序员？
 */

namespace app\index\controller;

use app\index\model\Group;
use app\index\model\Knowledge;
use think\Request;

class Repository extends Common
{
    public function __construct(Request $request = null, array $options = [])
    {
        parent::__construct($request, $options);
        $this->groupModel = new Group();
        $this->Knowledge = new Knowledge();
    }

    /**
     * 知识库页面
     * @return \think\response\View
     */
    public function index()
    {
        $group = $this->groupModel->getAllData();
        $this->assign('group', $group);
        $get = input('get.');
        $where = [];
        $where['a.status'] = ['neq', -1];
        if (isset($get['status']) && $get['status'] != 0) {
            $where['a.status'] = $get['status'];
        }
        if (isset($get['group_id']) && $get['group_id'] != 0) {
            $where['a.gid'] = $get['group_id'];
        }
        if (isset($get['keyword'])) {
            $where['a.title'] = ['like', '%' . $get['keyword'] . '%'];
        }
        $countPage = $this->Knowledge->getCountMsg($where);
        $this->assign('countPage', $countPage);

        return view("Repository/index");
    }

    /**
     * 获取知识库数据
     *
     */
    public function getDataByWhere()
    {
        $get = input('get.');
        $page = empty($get['page']) ? 1 : $get['page'];
        $count = empty($get['count']) ? 10 : $get['count'];
        $where = [];
        $where['a.status'] = ['neq', -1];
        if (isset($get['status']) && $get['status'] != 0) {
            $where['a.status'] = $get['status'];
        }
        if (isset($get['group_id']) && $get['group_id'] != 0) {
            $where['a.gid'] = $get['group_id'];
        }
        if (isset($get['keyword']) && $get['keyword'] != "") {
            $where['a.title'] = ['like', '%' . $get['keyword'] . '%'];
        }
        $data = $this->Knowledge->getPageData($page, $count, $where);
        return json(['data' => $data, 'status' => 1, 'msg' => '获取数据成功']);
    }

    /**
     * 添加文章
     * @return \think\response\View
     */
    public function add()
    {
        $group = $this->groupModel->getAllData();
        $this->assign('group', $group);
        return view("Repository/add");
    }


    /**
     * 修改文章
     * @return \think\response\View
     */
    public function edit()
    {
        $id = input('get.id');

        $group = $this->groupModel->getAllData();
        $this->assign('group', $group);

        $data = $this->Knowledge->getDataByWhere(['id' => $id]);
        $this->assign('data', $data);
        
        return view('Repository/edit');
    }

    /**
     * 保存数据
     */
    public function saveData()
    {
        $data = [];

        if (request()->isPost()) {
            $data = input('post.');
        } else {
            return json(['status' => -1, 'msg' => '未知数据']);
        }
        if ($data['title'] == "") {
            return ['status' => -1, 'msg' => "标题不能为空"];
        }
        if ($data['author'] == "") {
            return ['status' => -1, 'msg' => "来源不能为空"];
        }
        if ($data['image'] == "") {
            return ['status' => -1, 'msg' => "必须上传图片"];
        }
        if ($data['contents'] == "") {
            return ['status' => -1, 'msg' => "内容不能为空"];
        }
        if ($data['info'] == "") {
            return ['status' => -1, 'msg' => "简介不能为空"];
        }
        
        $data['contents'] = htmlspecialchars($data['contents'],ENT_QUOTES);
        
        if (empty($data['id'])) {
            //添加数据
            $data['times'] = time();
            $return = $this->Knowledge->saveData($data);
            if ($return) {
                return ['status' => 1, 'msg' => '添加成功'];
            } else {
                return ['status' => -1, 'msg' => $return];
            }
        } else {
            //修改数据
            $id = $data['id'];
            unset($data['id']);
            $return = $this->Knowledge->updateData($data, ['id' => $id]);
            return $return ? ['status' => 1, 'msg' => '修改成功'] : ['status' => -1, 'msg' => $return];
        }
    }


    /**
     * 修改状态
     * @param int $id 数据ID
     * @param int $status 状态
     * @return array 返回操作状态
     */
    public function editStatus()
    {
        $id = '';
        if (request()->isGet()) {
            $get = input('get.');
        } else {
            return json(['status' => -1, 'msg' => '未知数据']);
        }
        if (empty($get['id'])) {
            return json(['status' => -1, 'msg' => '未知数据']);
        }
        if (empty($get['status'])) {
            return json(['status' => -1, 'msg' => '未知数据']);
        }
        $data = ['status' => $get['status']];
        $return = $this->Knowledge->updateData($data, ['id' => $get['id']]);
        return $return == 1 ? ['status' => 1, 'msg' => '修改成功'] : ['status' => -1, 'msg' => '修改失败'];
    }

    /**
     * 删除数据
     */
    public function delMsg()
    {
        if (request()->isPost()) {
            $id = input('post.id');
        } else {
            return json(['status' => -1, 'msg' => '未知数据']);
        }
        $data = ['status' => -1];
        $return = $this->Knowledge->updateData($data, ['id' => $id]);
        return $return ? ['status' => 1, 'msg' => '删除成功'] : ['status' => -1, 'msg' => '删除失败'];
    }

    /**
     * 上传图片
     * @param $file  文件
     * @return mixed  返回对应错误数据
     */
    public function uploadImg()
    {
        //文件
        $file = request()->file('image');
        return uploadImg($file, '/upload/Repository/images/');
    }

}