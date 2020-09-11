<?php
/**
 * Created by PhpStorm.
 * User: yyy
 * Date: 18/6/8
 * Time: 下午4:07
 */
namespace Common\ControllerAdmin;

use Think\Controller;
use \yyy_base_lib\Functions\functions;
class baseAdmin extends Controller{

    /**
     * 部门code的对应model
     */
    const DEPT_MAP = [
        ['title'=>'年段'],
        ['title'=>'系', 'model' => 'Dept', 'keyid' => 'dep_no', 'field'=>'dep_name'],
        ['title'=>'班级', 'model' => 'Class', 'keyid' => 'class_no', 'field'=>'class_name'],
        ['title'=>'学生', 'model' => 'Vwrenlian', 'keyid' => '学号', 'field'=>'姓名'],
    ];
    protected function successJson($data = [], $msg = '成功') {
        $this->ajaxReturn([
            'data' => $data,
            'code' => 100,
            'msg' => $msg
        ]);
    }

    protected function failJson($msg = '失败', $data = [], $code = 101) {
        $this->ajaxReturn([
            'data' => $data,
            'code' => $code,
            'msg' => $msg
        ]);
    }

    public function get_config_secret($key){
        $config = BD('config_secret')->getOne(['key' => $key]);
        if (!$config) {
            return false;
        }
        $config = json_decode($config['value'], true);
        if (!$config) {
            return false;
        }
        return $config;
    }

    public function convert_api(&$data, $fields, $ext_fields = []){
        if (!$data || !is_array($data)) {
            return;
        }
        foreach ($data as $key => $da) {
            if (is_array($da)) {
                foreach ($da as $_k => $d) {
                    if (!empty($fields[$_k])) {
                        $data[$key][$fields[$_k]] = $d;
                    }
                    if ($ext_fields) {
                        foreach ($ext_fields as $k => $v) {
                            $data[$key][$k] = str_replace('{$id}', $da['id'], $v);
                        }
                    }
                }
            } else {
                if (!empty($fields[$key])) {
                    $data[$fields[$key]] = $da;
                }
                if ($ext_fields) {
                    foreach ($ext_fields as $k => $v) {
                        $data[$k] = str_replace('{$id}', $da['id'], $v);
                    }
                }
            }
        }
    }

    protected function _index($modelName = '', $where = [], $sort=['id'=>'desc'], $field='*') {
        $where['deleted'] = 0;
        if (empty($modelName)) {
            $modelName = empty($this->model_name) ? strtolower(CONTROLLER_NAME) : $this->model_name;
        }

        if (!empty($where['left_join'])) {
            $leftjoin = $where['left_join'];
            unset($where['left_join']);
            $sql = D($modelName);
            unset($where['deleted']);
            if (!isset($where[D($modelName)->getTableName().'.deleted'])) {
                $where[D($modelName)->getTableName().'.deleted'] = 0;
            }
            foreach ($leftjoin as $one) {
                $sql->join($one);
            }
            $list = $sql->field($field)->where($where)->order($sort)->page(I('page', 1), I('page_size', 20))->select();
            if (method_exists($this, 'convert_index')) {
                $list = $this->convert_index($list);
            }

            $sql = D($modelName);
            foreach ($leftjoin as $one) {
                $sql->join($one);
            }

            $count = $sql->where($where)->count();
        } else {
            $list = D($modelName)->field($field)->where($where)->order($sort)->page(I('page', 1), I('page_size', 20))->select();
            if (method_exists($this, 'convert_index')) {
                $list = $this->convert_index($list);
            }
            $count = D($modelName)->where($where)->count();
        }

        $has_more = functions\has_more($count, I('page', 1), I('page_size', 20));
        $this->successJson(['list'=>$list, 'count'=>$count, 'has_more'=>$has_more, 'page_total' => ceil($count/I('page_size', 20))]);
    }

    protected function _tree($modelName = '', $where = [], $sort=['id'=>'desc']) {
        $where['deleted'] = 0;
        if (empty($modelName)) {
            $modelName = empty($this->model_name) ? strtolower(CONTROLLER_NAME) : $this->model_name;
        }
        $list = D($modelName)->where($where)->order($sort)->select();
        if (!$list) {
            $this->successJson([]);
        }
        if (method_exists($this, 'convert_tree')) {
            $list = $this->convert_tree($list);
        }

        $tree = functions\list_to_tree($list);
        $this->successJson($tree);
    }

    protected function _all_list($modelName = '', $where=[], $sort=['id'=>'desc'], $field='*') {
        $where['deleted'] = 0;
        if (empty($modelName)) {
            $modelName = empty($this->model_name) ? strtolower(CONTROLLER_NAME) : $this->model_name;
        }
        if (!empty($where['left_join'])) {
            $leftjoin = $where['left_join'];
            unset($where['left_join']);
            $sql = D($modelName);
            unset($where['deleted']);
            if (!isset($where[D($modelName)->getTableName().'.deleted'])) {
                $where[D($modelName)->getTableName() . '.deleted'] = 0;
            }
            foreach ($leftjoin as $one) {
                $sql->join($one);
            }

            $list = $sql->field($field)->where($where)->order($sort)->select();
            if (method_exists($this, 'convert_all_list')) {
                $list = $this->convert_all_list($list);
            }
        } else {
            $list = D($modelName)->field($field)->where($where)->order($sort)->select();
            if (method_exists($this, 'convert_all_list')) {
                $list = $this->convert_all_list($list);
            }
        }
        $this->successJson($list);
    }

    protected function _info($modelName = '',$field='*') {
        $where = ['id' => I('id')];
        $where['deleted'] = 0;
        if (empty($modelName)) {
            $modelName = empty($this->model_name) ? strtolower(CONTROLLER_NAME) : $this->model_name;
        }
        $ret = D($modelName)->field($field)->where($where)->find();
        if (!$ret) {
            $this->failJson();
        } else {
            if (method_exists($this, 'convert_info')) {
                $ret = $this->convert_info($ret);
            }

            $this->successJson($ret);
        }
    }

    protected function _edit($modelName = '', $data = []) {

        $id = !empty($data['id']) ? $data['id'] : I('id');
        if (empty($modelName)) {
            $modelName = empty($this->model_name) ? strtolower(CONTROLLER_NAME) : $this->model_name;
        }

        foreach ($data as $k => $value) {
            if (is_array($value)) {
                $data[$k] = json_encode($value, JSON_UNESCAPED_UNICODE);
            }
        }

        if (!D($modelName)->create($data)) {
            $this->failJson(D($modelName)->getError());
        }
        if ($id) {
            $ret = $id;
            D($modelName)->where(['id' => $id])->save($data);
            admin_log::add_log($this->userInfo, basename(str_replace('\\', '/', get_class($this))).'_edit',['id'=>$id]);
            if (method_exists($this, 'after_edit')) {
                $ret = $this->after_edit($id, $data);
            }
        } else {
            $ret = D($modelName)->add($data);
            if ($ret) {
                admin_log::add_log($this->userInfo, basename(str_replace('\\', '/', get_class($this))).'_add', ['id'=>$ret]);
                if (method_exists($this, 'after_edit')) {
                    $ret = $this->after_edit($ret, $data);
                }
            }

        }
        if (!$ret) {
            $this->failJson('您可能没有更改信息');
        } else {
            $this->successJson($ret);
        }
    }

    protected function _sort($modelName = '') {
        $id = I('id');
        $sort = I('sort');

        if (empty($modelName)) {
            $modelName = empty($this->model_name) ? strtolower(CONTROLLER_NAME) : $this->model_name;
        }
        if ($id) {
            $ret = D($modelName)->where(['id' => $id])->save(['sort'=>$sort]);
        } else {
            $this->failJson();
        }

        if (!$ret) {
            $this->failJson();
        } else {
            $this->successJson();
        }
    }

    protected function _verify($modelName = '') {
//        $ret = $logic->verify($this->post['id'], $this->post['status']);
        $id = I('id');
        $status = I('status');
        if (empty($modelName)) {
            $modelName = empty($this->model_name) ? strtolower(CONTROLLER_NAME) : $this->model_name;
        }
        $ret = D($modelName)->where(['id' => $id])->save(['status'=>$status]);
        if (!$ret) {
            $this->failJson();
        } else {
            admin_log::add_log($this->userInfo, basename(str_replace('\\', '/', get_class($this))).'_verify', ['id'=>$id, 'status'=>$status]);
            $this->successJson();
        }
    }

    protected function _del($modelName = '') {
        $id = I('id');
        if (empty($modelName)) {
            $modelName = empty($this->model_name) ? strtolower(CONTROLLER_NAME) : $this->model_name;
        }
        $ret = D($modelName)->where(['id' => $id])->save(['deleted' => 1]);
        if (!$ret) {
            $this->failJson();
        } else {
            admin_log::add_log($this->userInfo, basename(str_replace('\\', '/', get_class($this))).'_del',['id'=>$id]);
            if (method_exists($this, 'after_del')) {
                $this->after_del($id);
            }

            $this->successJson();
        }
    }

    /**
     * 生成dept码
     * @param $depts
     * @return string
     */
    protected function genDept($depts) {
        if (!$depts) {
            return '';
        }
        $deptCode = [];
        foreach ($depts as $dept) {
            if ($dept && is_array($dept)) {
                $deptCode[] = join(',',$dept);
            } else {
                $deptCode[] = 0;
            }
        }
        return join(':', $deptCode);
    }

    /**
     * 解析dept码为数组
     * @param $deptStr
     * @return array
     */
    protected function parseDept($deptStr) {
        if (!$deptStr) {
            return [];
        }
        $depts = [];
        $deptCode = explode(':', $deptStr);
        foreach ($deptCode as $code) {
            if ($code) {
                $depts[] = explode(',', $code);
            } else {
                $depts[] = [0];
            }
        }
        return $depts;
    }

    /**
     * 解析dept码为具体文字
     * @param $deptStr
     * @return array
     */
    protected function convertDept($deptStr) {
        if (!$deptStr) {
            return [];
        }
        $depts = $this->parseDept($deptStr);
        $deptsConvert = [];

        foreach ($depts as $k => $dept) {
            $_one = self::DEPT_MAP[$k];
            if (!isset($_one)) {
                $deptsConvert[] = '-';
                continue;
            }
            if (count($dept) == 1 && $dept[0] == 0) {
                $title = '所有' . (self::DEPT_MAP[$k]['title'] ? self::DEPT_MAP[$k]['title'] : '-');
            } elseif($dept) {
                if (!empty(self::DEPT_MAP[$k]['model'])) {
                    $names = D(self::DEPT_MAP[$k]['model'])->field(self::DEPT_MAP[$k]['field'])->where([self::DEPT_MAP[$k]['keyid']=>['in', $dept]])->select();

                    $title = join(',', array_column($names, self::DEPT_MAP[$k]['field']));
                } else {
                    $title = join(',', $dept);
                }
            } else {
                $title = '-';
            }
            $deptsConvert[] = $title;
        }
        return $deptsConvert;
    }

    public function getAllDeptPurview()
    {
        $purview = [];
        foreach (self::DEPT_MAP as $one) {
            $purview[] = [0];
        }
        return $purview;
    }

    protected function getPage()
    {
        return [I('page', 1), I('page_size', 20)];
    }

    protected function _page($modelName = '', $where = [], $field='*', $sort='id asc') {
        $where['deleted'] = 0;
        if (empty($modelName)) {
            $modelName = empty($this->model_name) ? strtolower(CONTROLLER_NAME) : $this->model_name;
        }
        list($page, $pageSize) = $this->getPage();
        $count = D($modelName)->where($where)->count();
        $count = $count ? $count : 0;
        $list = [];
        if ($count) {
            $list = D($modelName)->field($field)->where($where)->order($sort)->page($page, $pageSize)->select();
        }
        $has_more = functions\has_more($count, $page, $pageSize);
        return ['list'=>$list, 'count'=>$count, 'has_more'=>$has_more];
    }

}