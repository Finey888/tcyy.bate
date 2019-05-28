<?php
/**
 * Created by IntelliJ IDEA.
 * User: Linda Rong (Rainbow)
 * Date: 2018/1/22 0022
 * Time: 19:13
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

namespace app\index\model;

use think\Db;
use think\Model;

class Knowledge extends Model
{

    // 设置数据表（不含前缀）
    protected $name = 'knowledge';

    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->_collection = Db::name($this->name);
    }

    public function getDataByWhere($where = [])
    {
        $res = $this->_collection->where($where)->find();
        return $res;
    }

    //分页数据
    public function getPageData($page = 1, $count = 10, $where = ['a.status' => ['neq', -1]])
    {
        $data = $this->_collection
            ->alias('a')
            ->where($where)
            ->join('tcyy_group b', 'a.gid=b.id', 'LEFT')
            ->field('a.id,a.image,a.title as ktitle,a.sort,a.status,b.title')
            ->page($page . ',' . $count)
            ->order('id desc')
            ->select();
        return $data;
    }

    //数据条数
    public function getCountMsg($where = ['a.status' => ['neq', -1]])
    {
        $data = $this->_collection
            ->alias('a')
            ->where($where)
            ->join('tcyy_group b', 'a.gid=b.id', 'LEFT')
            ->field('a.id,a.image,a.title as ktitle,a.sort,a.status,b.title')
            ->count();
        return $data;
    }

    /**
     * 保存数据
     * @param $data
     * @return int|string
     */
    public function saveData($data){
        $res = $this->_collection->insert($data);
        return $res;
    }

    /**
     * 修改数据
     * @param $data
     * @param $where
     * @return int|string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function updateData($data,$where){
        $res = $this->_collection->where($where)->update($data);
        return $res;
    }

    /**
     * 删除数据
     * @param $where
     * @return int
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function delMsg($where){
        $res = $this->_collection->where($where)->delete();
        return $res;
    }
}