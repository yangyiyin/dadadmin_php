<?php
/**
 * Created by PhpStorm.
 * User: yyy
 * Date: 18/8/20
 * Time: 上午10:00
 */
namespace Common\ControllerAdmin;
class admin_log extends baseAdminLogin{

    public function index() {
        $where = [];
        $optName = I('opt_name');
        if (!empty(I('opt_name'))) {
            $where['opt_name'] = ['like', '%'.$optName.'%'];
        }
        $actionName = I('action_name');
        if (!empty($actionName)) {
            $where['action'] = ['like', '%'.$actionName.'%'];
        }


        parent::_index('admin_log', $where);
    }

    public function convert_index($list) {
        if ($list) {
            $map = [];
            foreach ($list as $k => &$li) {
                $li['action_name'] = isset($map[$li['action']]) ? $map[$li['action']] : $li['action'];
            }
        }
        return $list;
    }


    static function add_log($userInfo=[], $action, $params=[]) {
        if (!$userInfo) {
            return;
        }

        $list = [];

        if (!isset($list[$action])) {
            return;
        }
        $data = [];
        $data['action'] = $list[$action];
        $data['params'] = $params ? substr(json_encode($params, JSON_UNESCAPED_UNICODE), 0, 999) : '';
        $data['opt_id'] = $userInfo['id'];
        $data['opt_name'] = $userInfo['user_name'];
        $data['opt_show_name'] = $userInfo['show_name'];
        D('admin_log')->add($data);
    }

    public function add_log_manual($actionName, $params=[]) {
        if (!$this->userInfo || !$actionName) {
            return;
        }

        $data = [];
        $data['action'] = $actionName;
        $data['params'] = $params ? substr(json_encode($params, JSON_UNESCAPED_UNICODE), 0, 999) : '';
        $data['opt_id'] = $this->userInfo['id'];
        $data['opt_name'] = $this->userInfo['user_name'];
        $data['opt_show_name'] = $this->userInfo['show_name'];
        D('admin_log')->add($data);
    }

}