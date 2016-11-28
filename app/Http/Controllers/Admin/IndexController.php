<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class IndexController extends CommonController
{
    public function index(){
        $user_info = session('user');
        return view('admin.index',$user_info);
    }

    // 显示系统信息
    public function info(){
        return view('admin.info');
    }

    // 更改管理员密码
    public function pass(){
        if($input = Input::all()){
            // 定义验证规则
            $rules = [
                'password' => 'required|between:6,20|confirmed',
            ];
            // 提示信息
            $message = [
                'password.required' => '新密码不能为空',
                'password.between'  => '新密码必须在6-20位之间',
                'password.confirmed'=> '新密码和确认密码不一致',
            ];
            $validator = Validator::make($input,$rules,$message);
            if($validator->passes()){//验证规则通过
                $user = User::first();
                $_password = Crypt::decrypt($user->user_pass);
                if($input['password_o']==$_password){
                    $user->user_pass = Crypt::encrypt($input['password']);
                    $user->update();
                    return back()->with('errors','密码修改成功');
                }else{
                    return back()->with('errors','原密码错误');
                }
            }else{
                //$validator->errors()->all();//测试打印错误信息
                return back()->withErrors($validator);
            }
        }else{
            return view('admin.pass');
        }
    }
}
