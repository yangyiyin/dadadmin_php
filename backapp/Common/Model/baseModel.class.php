<?php
namespace Common\Model;
class baseModel{
    public function __construct($name){
        $this->name = $name;
    }
    public function getOne($where, $field='*'){
        $where['deleted'] = 0;
        return D($this->name)->field($field)->where($where)->find();
    }

    public function get_all($where=[], $order=['id'=>'desc'],$field='*'){
        if ($where) {
            $where['deleted'] = 0;
        } else {
            $where = ['deleted'=>0];
        }
        return D($this->name)->field($field)->where($where)->order($order)->select();
    }

    public function get_list($where=[], $page=1, $page_size=20, $order=['id'=>'desc']){
        if ($where) {
            $where['deleted'] = 0;
        } else {
            $where = ['deleted'=>0];
        }

        if (is_array($order) && !isset($order['id'])) {
            $order = $order ? $order : [];
            $order['id'] = 'desc';
        }
        //$this->model->where($where)->order($order)->limit($page_size)->page($page)->select();

        return D($this->name)->where($where)->order($order)->limit($page_size)->page($page)->select();
    }

    public function get_count($where=[]){
        if ($where) {
            $where['deleted'] = 0;
        } else {
            $where = ['deleted'=>0];
        }
        return D($this->name)->where($where)->count();
    }

    public function get_info($id) {
        return D($this->name)->where(['id'=>$id])->find();
    }

    public function get_one($where) {
        $where['deleted'] = 0;
        return  D($this->name)->where($where)->find();

    }

    public function add_one($data) {
        $ret = D($this->name)->add($data);
        //echo $this->model->getDbError();
        if ($ret) {
            return D($this->name)->getLastInsID();
        } else {
            return false;
        }
    }

    public function add_all($data) {
        return D($this->name)->addAll($data);
    }

    public function del($id) {
        return D($this->name)->where(['id'=>$id])->save(['deleted'=>1]);
    }

    public function del_truely($where) {
        return D($this->name)->where($where)->delete();
    }

    public function del_by_where($where) {
        return D($this->name)->where($where)->save(['deleted'=>1]);
    }

    public function update_by_id($id, $data) {
        return D($this->name)->where(['id'=>$id])->save($data);
    }
    public function update_by_where($where, $data) {
        return D($this->name)->where($where)->save($data);
    }

    public function setInc($where, $field, $value) {
        return D($this->name)->where($where)->setInc($field, $value);
    }

    public function setDec($where, $field, $value) {
        return D($this->name)->where($where)->setDec($field, $value);
    }

    public function sum($where, $field) {
        return D($this->name)->where($where)->sum($field);
    }

    public function max($where, $field) {
        return D($this->name)->where($where)->max($field);
    }

    public function min($where, $field) {
        return D($this->name)->where($where)->min($field);
    }

    public function avg($where, $field) {
        return D($this->name)->where($where)->avg($field);
    }
}