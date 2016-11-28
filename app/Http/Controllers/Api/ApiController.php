<?php

namespace App\Http\Controllers\Api;

    use App\Http\Model\People;

    use App\Http\Requests;
    use App\Http\Controllers\Controller;
    use Illuminate\Support\Facades\Crypt;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Input;
    use Illuminate\Support\Facades\Validator;
    class ApiController extends Controller
{
    // APP登录
    public function login(){
        if($input = Input::all()){
            // 定义验证规则
            $rules = [
                'pwd' => 'required|between:6,20',
                'name' => 'alpha_num',
            ];
            // 提示信息
            $message = [
                'name.alpha_num' => '用户名只能是字符和数字',
                'pwd.required' => '密码不能为空',
                'pwd.between'  => '密码必须在6-20位之间',
            ];
            $validator = Validator::make($input,$rules,$message);
            if($validator->passes()){//验证规则通过
                // 首先在blog_people_index中验证用户是否存在
                $indexInfo = DB::table('people_index')->where('name','=',$input['name'])->first();
                if($indexInfo===null){
                    $data = array(
                        'sign' => 0,
                        'code' => 'register error',
                        'msg'  => '用户名不存在，请重新登录或注册新账号！'
                    );
                    return json_encode($data);
                }
                $userInfo = DB::table('people')->find($indexInfo->uid);
                if(Crypt::decrypt($userInfo->pwd)!=$input['pwd']){
                    $data = array(
                        'sign' => 0,
                        'code' => 'register error',
                        'msg'  => '密码错误，请重新登录！',
                    );
                    return json_encode($data);
                }else{
                    $data = array(
                        'sign' => 1,
                        'code' => 'register success',
                        'msg'  => '登录成功'
                    );
                    return json_encode($data);
                }
            }else {
                // 服务器端验证规则不通过
                $messages = $validator->messages();
                $data = array(
                    'sign' => 0,
                    'code' => 'insert error',
                    'msg' => $messages->first(),
                );
                echo json_encode($data);
                exit();
            }
        }
    }
    // APP注册
    public function register(){
        if($input = Input::all());
        // 定义验证规则
        $rules = [
            'pwd' => 'required|between:6,20',
            'name' => 'alpha_num',
            'name' => 'unique:people,tel',
            'email' => 'email',
            'tel' => 'integer',
        ];
        // 提示信息
        $message = [
            'name.alpha_num' => '用户名只能是字符和数字',
            'name.unique' => '该用户已经存在请重新输入',
            'email.email' => '请输入合法邮箱',
            'tel.integer' => '请输入合法手机号码',
            'pwd.required' => '密码不能为空',
            'pwd.between'  => '密码必须在6-20位之间',
        ];
        $validator = Validator::make($input,$rules,$message);
        if($validator->passes()){//验证规则通过
            $pwd   = $input['pwd'];
            $tel   = $input['tel'];
            $email = $input['email'];
            // 检查用户名是否存在
            $name = People::where('name','=',$input['name'])->first();
            $tel = People::where('tel','=',$input['tel'])->first();
            $email = People::where('email','=',$input['email'])->first();
            if($name!=null){
                $data = array(
                'sign' => 0,
                'code' => 'register error',
                'msg' => '该用户名已经存在，请返回登录或重新注册！'
                 );
                return json_encode($data);
            }
            if($tel!=null){
                $data = array(
                'sign' => 0,
                'code' => 'register error',
                'msg' => '该手机已经注册，请返回登录或重新注册！'
                 );
                return json_encode($data);
            }
            if($email!=null){
                $data = array(
                'sign' => 0,
                'code' => 'register error',
                'msg' => '该邮箱已经存在，请返回登录或重新注册！'
                 );
                return json_encode($data);
            }
            $uid = DB::table('people_uid')->insertGetId(array());
            $input['uid']=$uid;
            $input['pwd'] = Crypt::encrypt($input['pwd']);
            DB::table('people')->insert($input);
            DB::table('people_index')->insert(array('uid'=>$uid,'name'=>$input['name']));
            DB::table('people_index')->insert(array('uid'=>$uid,'name'=>$input['tel']));
            $res = DB::table('people_index')->insert(array('uid'=>$uid,'name'=>$input['email']));
            if($res){
                // 成功
                $data = array(
                    'sign' => 1,
                    'code' => 'register success',
                    'msg' => '注册成功！'
                );
                echo json_encode($data);exit();
            }else{
                // 入库失败
                $data = array(
                    'sign' => 0,
                    'code' => 'insert error',
                    'msg' => '入库失败请联系管理员！'
                );
                echo json_encode($data);exit();
            }
        }else{
            // 服务器端验证规则不通过
            $messages = $validator->messages();
            $data = array(
                'sign' => 0,
                'code' => 'insert error',
                'msg' => $messages->first(),
            );
            echo json_encode($data);exit();
        }
    }
}