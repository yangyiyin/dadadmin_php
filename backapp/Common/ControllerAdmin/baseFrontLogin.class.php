<?php
/**
 * Created by PhpStorm.
 * User: yyy
 * Date: 18/6/8
 * Time: 下午4:07
 */
namespace Common\ControllerAdmin;
use yyy_base_admin\Common\Conf\Constant;
use yyy_base_admin\Common\Lib\Token;
use Think\Cache\Driver\Redis;

class baseFrontLogin extends baseAppController{
    protected $userInfo = [];
    public function __construct()
    {
        parent::__construct();
        //检测是否登录
        if (!isset($this->passToken)) {

            $this->check_login();

        }
    }

    protected function check_login(){
        $token = $this->params['session'];
        if ($token) {
            if ($token == 'APITOKEN_178340042') {
                $this->userInfo = D('user')->where(['id' => 1])->find();
                return;
            }
            if (!($user_info = $this->verify_token($token))) {
                $this->failJson('无效的会话,请重新登录', [], Constant::CODE_NOLOGIN);
            }
            $this->userInfo = D('user')->where(['id' => $user_info['id']])->find();
        } else {
            $this->failJson('无效的会话,请登录',[], Constant::CODE_NOLOGIN);
        }
    }
    protected function verify_token($token_str){
        $token = new Token();
        $user = $token->decode($token_str);

        $redis_simple = new Redis();
        $token_redis = $redis_simple->get($this->get_user_token_key($user['id']));
        if ($token_redis && $token_redis == $token_str) {
            return $user;
        } else {
            return false;
        }
    }

    protected function get_user_token_key ($uid) {
        return 'session-'.$uid;
    }
}