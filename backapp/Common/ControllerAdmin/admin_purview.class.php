<?php
/**
 * Created by PhpStorm.
 * User: yyy
 * Date: 18/8/20
 * Time: 上午10:00
 */
namespace Common\ControllerAdmin;
use Think\Cache\Driver\Redis;

class admin_purview extends baseAdminLogin{
    const TYPE_NORMAL = 0;
    const TYPE_MENU = 1;
    const TYPE_APP_MENU = 2;

    public function index() {
        $where = [];

        if (!empty(I('name'))) {
            $where['name'] = I('name');
        }

        if (I('type') != -1) {
            $where['type'] = I('type');
        }

        parent::_index('admin_purview', $where, ['sort'=>'desc']);
    }

    public function tree(){
        $where = [];

        if (!empty(I('name'))) {
            $where['name'] = I('name');
        }

        if (I('type') != -1) {
            $where['type'] = I('type');
        }

        parent::_tree('admin_purview', $where, ['type'=>'desc','sort'=>'desc']);
    }

    public function convert_tree($list) {
        if ($list) {
            foreach ($list as &$li) {
                $li['label'] = $li['name'];
            }
        }
        return $list;
    }

    public function all_list() {

        $where = [];
        if (I('type') != -1) {
            $where['type'] = I('type');
        }

        $where['deleted'] = 0;

        $list = D('admin_purview')->where($where)->order(['sort'=>'desc'])->select();
        $list = \yyy_base_lib\Functions\functions\convert_list_tree_depth($list);
        if (method_exists($this, 'convert_all_list')) {
            $list = $this->convert_all_list($list);
        }
        $this->successJson($list);

    }
    public function convert_all_list($list) {
        if ($list) {
            foreach ($list as &$li) {
                $prefix = '';
                for($i=0;$i<$li['depth'];$i++) {
                    $prefix.= '--';
                }
                $li['name'] = $prefix . $li['name'];
            }
        }
        return $list;
    }

    public function info() {
        parent::_info('admin_purview');
    }

    public function edit() {
        $data = [
            'type' => I('type'),
            'menu_name' => I('name'),
            'name' => I('name'),
            'uri' => I('uri'),
            'uri_md5' => md5(I('uri')),
            'pid' => I('pid'),
            'ico' => I('ico'),
        ];
        if (I('sort')) {
            $data['sort'] = I('sort');
        }
        parent::_edit('admin_purview', $data);
    }

    public function sort() {
        parent::_sort('admin_purview');
    }

    public function verify() {
        parent::_verify('admin_purview');
    }

    public function del() {
        parent::_del('admin_purview');
    }

    public function get_menu() {
        if (!isset($this->userInfo)) {
            $this->successJson([]);
        }
        if (self::isAdmin($this->userInfo['id'])) {
            $where = [];
            $where['type'] = self::TYPE_MENU;
            parent::_tree('admin_purview', $where, ['sort'=>'desc']);
        } else {
            $group = D('admin_group')->where(['id' => $this->userInfo['group_id']])->find();
            $purviews_all = $group['purviews_all'] ? explode(',', $group['purviews_all']) : [];
            if (!$purviews_all) {
                $this->successJson([]);
            }

            $where = [];
            $where['type'] = self::TYPE_MENU;
            $where['uri'] = ['in', $purviews_all];

            parent::_tree('admin_purview', $where, ['sort'=>'desc']);
        }
    }

    static function check_purview($purview,$user_info) {
        if (!$user_info) {
            return false;
        }
        $uid = $user_info['id'];
        $gid = $user_info['group_id'];

        if (self::isAdmin($uid)) {
            return true;
        }
        //从缓存获取
        $redis_simple_lib = new Redis();
        $key = self::get_group_purview_redis_key($gid);
        $purviews_all_str = $redis_simple_lib->get($key);
        if (!$purviews_all_str) {
            $group = D('admin_group')->where(['id' => $gid])->find();
            $purviews_all_str = (isset($group['purviews_all']) && $group['purviews_all']) ? $group['purviews_all'] : '';
            if ($purviews_all_str) {
                $redis_simple_lib->set($key, $purviews_all_str);
            }
        }
        $purviews_all = $purviews_all_str ? explode(',', $purviews_all_str) : [];
        $purview = strtolower($purview);
        foreach ($purviews_all as $_purviews_all) {
            $_purviews_all = strtolower($_purviews_all);
            if (strstr($_purviews_all, '::')) {//表示正则
                $rule = str_replace('::', '', $_purviews_all);
                $rule = '/' . str_replace('/', '\/', $rule) . '/';

//                var_dump($rule);
//                var_dump($purview);
//                var_dump(preg_match($rule, $purview));
                if (preg_match($rule, $purview)) {
                    return true;
                }
            } elseif($_purviews_all == $purview) {
                return true;
            }
        }
        return false;
    }
    static function isAdmin($uid) {
        return $uid == 1;
    }

    static function get_group_purview_redis_key($gid) {
        return '-group_purview-'.$gid;
    }
}