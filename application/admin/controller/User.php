<?php
namespace app\admin\controller;

use app\admin\controller\Base;
use app\index\model\User as UserModel;
use think\Request;
use think\Session;
use think\Db;

class User extends Base
{
    public function login()
    {
        $this -> view -> assign('title', '管理员登录');
        $this -> view -> assign('keywords', 'opto后台');
        $this -> view -> assign('desc', '光电网');
        $this -> view -> assign('copyRight', '光电141 www.opto.com');
        return $this->view ->fetch();
    }

    public function checkLogin(Request $request)
    {
        $status = 0; //验证失败标志
       $result = '验证失败'; //失败提示信息
       $data = $request -> param();

        //验证数据
        $validate=\think\Loader::validate('User');

        //通过验证后,进行数据表查询
        //此处必须全等===才可以,因为验证不通过,$result保存错误信息字符串,返回非零
        if ($validate->check($data)) {

           //查询条件
            $map = [
               'name' => $data['name'],
               'password' => md5($data['password'])
           ];

            //数据表查询,返回模型对象
            $user = Db::table('user')->where($map)->find();
            if (null === $user) {
                $result = '没有该用户,请检查';
            } else {
                $status = 1;
                $result = '验证通过,点击[确定]后进入后台';

                //创建2个session,用来检测用户登陆状态和防止重复登陆
                Session::set('user_id', $user -> id);
                Session::set('user_info', $user -> getData());

                //更新用户登录次数:自增1
                $user -> setInc('login_count');
            }
        } else {
            $result= $validate->getError();
        }
        return ['status'=>$status, 'message'=>$result, 'data'=>$data];
    }

    public function logout()
    {
    }
}
