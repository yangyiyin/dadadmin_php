<?php
/**
 * Created by PhpStorm.
 * User: yyy
 * Date: 18/8/20
 * Time: 上午10:00
 */
namespace Common\ControllerAdmin;
use Think\Cache\Driver\Redis;

class admin_group extends baseAdminLogin {

    public function index() {
        $where = [];

        if (!empty(I('name'))) {
            $where['name'] = I('name');
        }
        parent::_index('admin_group', $where, ['sort'=>'desc']);
    }


    public function all_list() {
        parent::_all_list('admin_group', [], ['sort'=>'desc']);
    }

    public function info() {
        parent::_info('admin_group');
    }

    public function edit() {
        $data = [];
        if (I('name', null) !== null) {
            $data['name'] = I('name');
        }
        if (I('purviews_half', null) !== null || I('purviews', null) !== null) {
            $data['purviews'] = I('purviews');
            $data['purviews_half'] = I('purviews_half');
            $data['purviews_all'] = $data['purviews'];
            $data['purviews_all'] = $data['purviews_half'] ? ($data['purviews_all'] ? $data['purviews_all'] . ',' . $data['purviews_half'] : $data['purviews_half']) : $data['purviews_all'];
        }
        parent::_edit('admin_group', $data);
    }

    public function after_edit($id, $data){
        if (!$id) {
            return false;
        }
        if (isset($data['purviews_all']) && $data['purviews_all']) {
            $redis_simple_lib = new Redis();
            $key = admin_purview::get_group_purview_redis_key($id);
            $ret = $redis_simple_lib->set($key, $data['purviews_all']);
            if (!$ret) {
                return false;
            }
        }
        return true;
    }

    public function sort() {
        parent::_sort('admin_group');
    }

    public function verify() {
        parent::_verify('admin_group');
    }

    public function del() {
        parent::_del('admin_group');
    }

}