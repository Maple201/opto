<?php
namespace app\admin\validate;

use think\Validate;

class User extends Validate
{
    protected $rule = [
    'name|管理员' =>'require',
    'password|密码'=>'require',
     'captcha|验证码'=>'require|captcha'
  ];
    protected $message=[
    'name.require'=>'请输入管理员',
    'password.require'=>'请输入密码',
     'captcha.require'=>'请输入验证码',



  ];
}
