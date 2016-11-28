<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\Navs;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class NavsController extends CommonController
{
    // 显示全部导航
    public function index(){
        $data = Navs::orderBy('nav_order','asc')->get();
        return view('admin.navs.index',compact('data'));
    }

    // 添加导航
    public function create(){
        return view('admin.navs.add');
    }
    // 添加导航提交方法
    public function store(){
        $input = Input::except('_token');
        $rules = [
            'nav_name'     => 'required',
            'nav_url'      => 'required',
        ];
        $message = [
            'nav_name.required' => '导航名称不能为空',
            'nav_url.required'  => '导航地址不能为空',
        ];
        $validator = Validator::make($input,$rules,$message);
        if($validator->passes()){
            $re = Navs::create($input);
            if($re){
                return redirect('admin/navs');
            }else{
                return back()->with('errors','导航添加失败，请稍后重试...');
            }
        }else{
            return back()->withErrors($validator);
        }
    }
    // 编辑导航
    public function edit($nav_id){
        $nav_info = Navs::find($nav_id);
        return view('admin.navs.edit',compact('nav_info'));
    }
    // 编辑导航提交的方法
    public function update($nav_id){
        $input = Input::except('_token','_method');
        $rules = [
            'nav_name' => 'required',
            'nav_url'  => 'required',
        ];
        $message = [
            'nav_name.required'    => '导航名称不能为空',
            'nav_url.required'     => '导航地址不能为空',
        ];
        $validator = Validator::make($input,$rules,$message);
        if($validator->passes()){
            $re = Navs::where('nav_id',$nav_id)->update($input);
            if($re){
                return redirect('admin/navs');
            }else{
                return back()->with('errors','导航更新失败,请稍后重试...');
            }
        }else{
            return back()->withErrors($validator);
        }
    }
    // 删除导航
    public function destroy($nav_id){
        $re = Navs::where('nav_id',$nav_id)->delete();
        if($re){
            $data = [
                'status'    => 0,
                'msg'       => '导航删除成功',
            ];
        }else{
            $data = [
                'status'    => 1,
                'msg'       => '导航删除失败',
            ];
        }
        return $data;
    }
    // 导航排序
    public function changeorder(){
        $input = Input::all();
        $link = Navs::find($input['nav_id']);
        $link -> nav_order = $input['nav_order'];
        if($link->update()){
            $data = [
                'status'  => 0,
                'msg'     => '导航排序修改成功',
            ];
        }else{
            $data = [
                'status'  => 1,
                'msg'     => '导航排序修改失败',
            ];
        }
        return $data;
    }
}
