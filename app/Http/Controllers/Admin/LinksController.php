<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\Links;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class LinksController extends CommonController
{
    // 显示全部友情链接
    public function index(){
        $data = Links::orderBy('link_order','asc')->get();
        return view('admin.links.index',compact('data'));
    }

    // 添加友情链接
    public function create(){
        return view('admin.links.add');
    }
    // 添加友情链接提交方法
    public function store(){
        $input = Input::except('_token');
        $rules = [
            'link_name'     => 'required',
            'link_url'      => 'required',
        ];
        $message = [
            'link_name.required' => '友情链接名称不能为空',
            'link_url.required'  => '友情链接地址不能为空',
        ];
        $validator = Validator::make($input,$rules,$message);
        if($validator->passes()){
            $re = Links::create($input);
            if($re){
                return redirect('admin/links');
            }else{
                return back()->with('errors','友情链接添加失败，请稍后重试...');
            }
        }else{
            return back()->withErrors($validator);
        }
    }
    // 编辑友情链接
    public function edit($link_id){
        $link_info = Links::find($link_id);
        return view('admin.links.edit',compact('link_info'));
    }
    // 编辑友情链接提交的方法
    public function update($link_id){
        $input = Input::except('_token','_method');
        $rules = [
            'link_name' => 'required',
            'link_url'  => 'required',
        ];
        $message = [
            'link_name.required'    => '友情链接名称不能为空',
            'link_url.required'     => '友情链接地址不能为空',
        ];
        $validator = Validator::make($input,$rules,$message);
        if($validator->passes()){
            $re = Links::where('link_id',$link_id)->update($input);
            if($re){
                return redirect('admin/links');
            }else{
                return back()->with('errors','友情链接更新失败,请稍后重试...');
            }
        }else{
            return back()->withErrors($validator);
        }
    }
    // 删除友情链接
    public function destroy($link_id){
        $re = Links::where('link_id',$link_id)->delete();
        if($re){
            $data = [
                'status'    => 0,
                'msg'       => '友情链接删除成功',
            ];
        }else{
            $data = [
                'status'    => 1,
                'msg'       => '友情链接删除失败',
            ];
        }
        return $data;
    }
    // 友情链接排序
    public function changeorder(){
        $input = Input::all();
        $link = Links::find($input['link_id']);
        $link -> link_order = $input['link_order'];
        if($link->update()){
            $data = [
                'status'  => 0,
                'msg'     => '友情链接排序修改成功',
            ];
        }else{
            $data = [
                'status'  => 1,
                'msg'     => '友情链接排序修改失败',
            ];
        }
        return $data;
    }
}
