<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;

// 引入验证码类
require 'resources/org/code/Code.class.php';

class LoginController extends CommonController
{
    // 登录页面
    public function login(){
        if($input = Input::all()){
            $code = new \Code();
            $_code = $code->get();
            if(strtoupper($input['code'])!=$_code){
                //返回前一请求的页面，提示信息存储在session中
                return back()->with('msg','验证码错误');
            }
            $user = User::first();
            if($user->user_name!=$input['user_name'] || decrypt($user->user_pass)!=$input['user_pass']){
                return back()->with('msg','用户名或密码错误');
            }
            session(['user'=>$user]);
            return redirect('admin');
        }else{
            return view('admin.login');
        }
    }
    // 验证码
    public function code(){
        $code = new \Code();
        $code->make();
    }

    public function quit(){
        session(['user'=>null]);
        return redirect('admin/login');
    }
}
