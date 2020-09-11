<?php
namespace Common\ControllerAdmin;
class baseAppController extends baseAdmin {
    protected $actions = [];
    protected $edit_data = [];
    protected $list_field = '*';
    protected $info_field = '*';
    protected $list_sort = ['id'=>'desc'];
    protected $condition_where = ['id'=>'xxx'];
    protected $params = [];
    public function __construct()
    {
        $this->params = array_merge(I('param.'), I('get.'));
        parent::__construct();
    }
    protected function getWhere(){
        $where = [];
        if (!empty($this->condition_where)) {
            foreach ($this->condition_where as $key => $condition) {
                if (strpos($key, '.') !== false) {
                    list($a,$param_key) = explode('.', $key);
                } else {
                    $param_key = $key;
                }

                if (!empty(I($param_key, null)) || I($param_key, '') !== '') {
                    if (substr($condition,0,1) == '%') {
                        $where[$key] = ['like', str_replace('xxx', I($param_key), $condition)];
                    } else {
                        $where[$key] = str_replace('xxx', I($param_key), $condition);
                    }
                }
            }
        }
        return $where;
    }

    public function index(){
        if (!in_array('index', $this->actions)) {
            return $this->failJson('非法操作');
        }
        $where = [];
        if (method_exists($this, 'getWhere')) {
            $where = $this->getWhere();
        }
        parent::_index('', $where, empty($this->list_sort) ? ['id'=>'desc'] : $this->list_sort, empty($this->list_field) ? '*' : $this->list_field);
    }
    public function info(){
        if (!in_array('info', $this->actions)) {
            return $this->failJson('非法操作');
        }
        parent::_info('', empty($this->info_field) ? '*' : $this->info_field);
    }
    public function edit(){
        if (!in_array('edit', $this->actions)) {
            return $this->failJson('非法操作');
        }

        if (method_exists($this, 'beforeEdit')) {
            $this->beforeEdit();
        }

        parent::_edit('', !empty($this->edit_data) ? $this->edit_data : I('param.'));
    }
    public function verify(){
        if (!in_array('verify', $this->actions)) {
            return $this->failJson('非法操作');
        }
        parent::_verify();
    }
    public function del(){
        if (!in_array('del', $this->actions)) {
            return $this->failJson('非法操作');
        }
        parent::_del();
    }
    public function sort(){
        if (!in_array('sort', $this->actions)) {
            return $this->failJson('非法操作');
        }
        parent::_sort();
    }
    public function all_list(){
        if (!in_array('all_list', $this->actions)) {
            return $this->failJson('非法操作');
        }
        $where = [];
        if (method_exists($this, 'getWhere')) {
            $where = $this->getWhere();
        }
        parent::_all_list('', $where, empty($this->list_sort) ? ['id'=>'desc'] : $this->list_sort, empty($this->list_field) ? '*' : $this->list_field);
    }
}