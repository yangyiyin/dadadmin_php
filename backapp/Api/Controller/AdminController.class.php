<?php
/**
 * Created by PhpStorm.
 * User: yyy
 * Date: 18/6/8
 * Time: 下午4:07
 */
namespace Api\Controller;
use Common\ControllerAdmin\admin_log;
use Common\ControllerAdmin\baseAdmin;
use Common\Lib\Token;
use Think\Cache\Driver\Redis;

class AdminController extends baseAdmin {

    public function login() {
        $this->_login(I('user_name'), I('password'));
    }

    private function _login($user_name, $password) {
        if (!$user_name || !$password) {
            $this->failJson('参数错误');
        }
        $model = BD('admin_user');
        $user = $model->getOne(['user_name'=>$user_name]);
        if (!$user) {
            $this->failJson('用户名错误或密码错误');
        }

        if(empty($user['password'])) {
            $this->failJson('当前用户不允许登录');
        }

        if (md5($password) != $user['password']) {
            $this->failJson('用户名或密码错误');
        }

        if ($user['status'] == 0) {
            $this->failJson('当前用户已禁用，请联系系统管理员');
        }

        //生成token
        $token = new Token();
        $token_str = $token->encode($user);
        $redis_simple = new Redis();
        $redis_simple->set($this->get_user_token_key($user['id']), $token_str, 15 * 86400);//15天

        //记录
        admin_log::add_log($user, 'login',[]);
        $this->successJson(['token'=>$token_str, 'user_info'=>$user]);
    }

    public function get_user_token_key ($uid) {
        return 'token-'.$uid;
    }

}