<?php
/**
 * Created by PhpStorm.
 * User: yyy
 * Date: 18/8/20
 * Time: 上午10:00
 */
namespace Common\ControllerAdmin;
class admin_user extends baseAdminLogin{

    public function index() {
        $where = [];

        if (!empty(I('user_name'))) {
            $where['user_name'] = ['like', '%'.I('user_name').'%'];
        }
        if (!empty(I('area_id'))) {
            $where['area_id'] = I('area_id');
        }
        parent::_index('admin_user', $where);
    }

    public function convert_index($list) {
        if ($list) {
            $group_ids = \yyy_base_lib\Functions\functions\result_to_array($list, 'group_id');
            $groups = D('admin_group')->where(['group_id'=>['in',$group_ids]])->select();
            $groups_map = \yyy_base_lib\Functions\functions\result_to_map($groups, 'id');
            foreach ($list as $k => &$li) {
                $li['group_name'] = isset($groups_map[$li['group_id']]) ? $groups_map[$li['group_id']]['name'] : '';
               // var_dump($this->convertDept($li['dept']));
                $li['depts'] = \yyy_base_lib\Functions\functions\format_string(join(':', $this->convertDept($li['dept'])), 30);
                //$li['depts'] = $li['dept'];
            }
        }
        return $list;
    }

    public function get_all() {
        $where = [];
        parent::_all_list('admin_user', $where);
    }

    public function info() {
        parent::_info('admin_user');
    }

    public function convert_info($info) {
        if ($info) {
            $info['dept'] = $this->parseDept($info['dept']);
            if ($info['dept']) {
                foreach ($info['dept'] as &$dept) {
                    if (count($dept) == 1 && $dept[0] == 0) {
                        $dept = [];
                    }
                }
            }
        }
        return $info;
    }

    public function edit() {
        $data = I('param.');
        I('user_name',null) !== null && $data['user_name'] = I('user_name');
        I('show_name',null) !== null && $data['show_name'] =  I('show_name');
        I('password',null) !== null && $data['password'] = md5(I('password'));
        I('group_id',null) !== null && $data['group_id'] =  I('group_id');


        if (I('id') && I('password') == '******') {
            unset($data['password']);
        }
        if (!I('id') && BD('admin_user')->getOne(['user_name' => $data['user_name']])) {
            $this->failJson('用户名重复!');
        }
        if(!$data) $this->failJson('参数错误01!');

        $dept = I('dept',null);
        if ($dept) {
            $depts = [$dept['select_year'], $dept['select_dept'], $dept['select_class'], $dept['select_student']];
            $data['dept'] = $this->genDept($depts);
        } else {
//            $this->failJson('参数错误dept!');
        }

        $area_ids = I('area_ids',null);
        if ($area_ids) {
            $data['area_id'] = end($area_ids);
            $areas = BD('area')->get_all(['id'=>['in',$area_ids]], ['id'=>'asc']);
            $data['area_name'] = !empty($areas) ? join('/', array_column($areas, 'name')) : '';
            if ($data['area_id'] == -1) {
                $data['area_name'] = '全部';
            }
        } else {

        }

        parent::_edit('admin_user', $data);
    }

    public function sort() {
        parent::_sort('admin_user');
    }

    public function verify() {
        parent::_verify('admin_user');
    }

    public function del() {
        parent::_del('admin_user');
    }


}