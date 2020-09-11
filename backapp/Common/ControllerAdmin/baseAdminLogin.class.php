<?php
/**
 * Created by PhpStorm.
 * User: yyy
 * Date: 18/6/8
 * Time: 下午4:07
 */
namespace Common\ControllerAdmin;
use Common\Lib\Token;
use Think\Cache\Driver\Redis;

class baseAdminLogin extends baseAppController{
    protected $userInfo = [];
    public function __construct()
    {
        parent::__construct();
        //检测是否登录
        if (!isset($this->passToken)) {

            $token = $this->params['token'];
            if ($token) {//后台
                if (!($user_info = $this->verify_token($token))) {
                    $this->failJson('无效的会话,请重新登录', [], 999);
                }
                $this->userInfo = D('admin_user')->where(['id' => $user_info['id']])->find();
                //var_dump(CONTROLLER_NAME . '/' . ACTION_NAME);
                $purview = (CONTROLLER_NAME . '/' . ACTION_NAME);

                if (!admin_purview::check_purview($purview, $this->userInfo)) {
                    $this->failJson('您无权限访问该功能');
                }
            } else {
                $this->failJson('无效的会话,请登录',[], 999);
            }

        }
    }

    public function verify_token($token_str){
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

    public function get_user_token_key ($uid) {
        return 'token-'.$uid;
    }

    /**
     * 检测部门权限
     * @param $dept
     * @return bool
     */
    public function checkDeptPurview($dept) {
        if (admin_purview::isAdmin($this->userInfo['id'])) {
            return true;
        }
        if (!$dept) {
            return true;
        }
        $userDept = $this->parseDept($this->userInfo['dept']);
        if (!$userDept) {
            return '您没有权限查看该数据统计,请联系管理员开通相应权限';
        }

        foreach ($dept as $k => $one) {
            if (empty($userDept[$k]) || empty($userDept[$k][0])) {
                continue;
            }
            //下面表示我的该区间权限不为空
            if (empty($one)) {//如果要求权限为空，表示需要全部
                return '请选择'.(parent::DEPT_MAP[$k]['title'] ? parent::DEPT_MAP[$k]['title'] : '选项');
            }
            //通过查询，检测是否在区间内
            foreach ($one as $_one) {
                if (!in_array($_one, $userDept[$k])) {
                    return '当前' . (parent::DEPT_MAP[$k]['title'] ? parent::DEPT_MAP[$k]['title'] : '-') . '您没有权限查看';
                }
            }
        }
        return true;
    }
}