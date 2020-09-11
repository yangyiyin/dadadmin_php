<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2020/4/8
 * Time: 9:25
 */

namespace Api\Controller;
use Common\ControllerAdmin\baseAppController;

class ConfigController extends baseAppController
{
    protected $actions = [
        'index','info','edit',
//        'verify','del','sort','all_list'
    ];
    public function valuebykey() {

        $info = BD('config')->get_one(['key' => I('key')]);
        $value = false;
        if ($info) {
            $value = json_decode($info['value']);
        }
        $this->successJson($value);
    }

    public function edit() {
        $data = I('data');
        if (!$data) {
            $this->successJson();
        }

        foreach ($data as $item) {
            if (empty($item['key'])) continue;
            if (BD('config')->get_one(['key'=>$item['key']])) {
                D('config')->update_by_key($item['key'], $item['value']);
            } else {
                $add_data = [
                    'key'=>$item['key'],
                    'value'=>$item['value'],
                    'remark'=>$item['remark']
                ];
                BD('config')->add_one($add_data);
            }

        }

        $this->successJson();
    }
}