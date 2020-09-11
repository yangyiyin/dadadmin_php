<?php
/**
 * Created by PhpStorm.
 * User: yyy
 * Date: 18/6/8
 * Time: 下午8:31
 */
namespace Api\Model;
use Think\Model;

class ConfigModel extends Model {

    public function update_by_key($key, $value) {
        return $this->where(['key'=>$key])->save(['value'=>$value]);
    }

    public function get_value_by_key($key)
    {
        $config = $this->where(['key' => $key])->find();
        return $config ? $config['value'] : false;
    }
}