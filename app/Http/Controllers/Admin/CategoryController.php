<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\Category;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class CategoryController extends CommonController
{
    // 全部分类列表
    public function index(){
        $cate_model = new Category();
        $data = $cate_model->cate_list();
        return view('admin.category.index')->with('data',$data);
    }

    // 添加分类
    public function create(){
        $data = Category::where('cate_pid',0)->get();
        return view('admin.category.add',compact('data'));
    }
    // 添加分类提交方法
    public function store(){
        if($input = Input::except('_token')){
            // 定义验证规则
            $rules = [
                'cate_name' => 'required|between:1,6',
            ];
            // 提示信息
            $message = [
                'cate_name.required' => '分类名称不能为空',
                'cate_name.between'  => '分类名称必须在1-6位之间',
            ];
            $validator = Validator::make($input,$rules,$message);
            if($validator->passes()){//验证规则通过
                $re = Category::create($input);
                if($re){
                    return redirect('admin/category');
                }else{
                    return back()->with('errors','数据填充失败');
                }
            }else{
                //$validator->errors()->all();//测试打印错误信息
                return back()->withErrors($validator);
            }
        }
    }
    // 编辑分类
    public function edit($cate_id){
        $cate_info = Category::find($cate_id);
        $data = Category::where('cate_pid',0)->get();
        return view('admin.category.edit',compact('cate_info','data'));
    }
    // 更新分类
    public function update($cate_id){
        if($input = Input::except('_token','_method')){
            // 定义验证规则
            $rules = [
                'cate_name' => 'required|between:1,6',
            ];
            // 提示信息
            $message = [
                'cate_name.required' => '分类名称不能为空',
                'cate_name.between'  => '分类名称必须在1-6位之间',
            ];
            $validator = Validator::make($input,$rules,$message);
            if($validator->passes()){//验证规则通过
                $re = Category::where('cate_id',$cate_id)->update($input);
                if($re){
                    return redirect('admin/category');
                }else{
                    return back()->with('errors','数据填充失败');
                }
            }else{
                //$validator->errors()->all();//测试打印错误信息
                return back()->withErrors($validator);
            }
        }
    }
    // 显示单个分类
    public function show(){

    }
    // 删除分类
    public function destroy($cate_id){
        $re = Category::where('cate_id',$cate_id)->delete();
        // 如果删除父类，所有的子类全部变成顶级分类
        Category::where('cate_pid',$cate_id)->update(['cate_pid'=>0]);
        if($re){
            $data = [
                'status' => 0,
                'msg'    => '分类删除成功',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg'    => '分类删除失败,请稍后重试...',
            ];
        }
        return $data;
    }
    // 分来排序
    public function changeOrder(){
        $input = Input::all();
        $cate = Category::find($input['cate_id']);
        $cate->cate_order = $input['cate_order'];
        if($cate->update()){
            $data = [
                'status' => 0,
                'msg'    => '分类排序更新成功',
            ];
        }else{
            $data = [
                'status'    => 1,
                'msg'       => '分类排序更新失败,请稍后重试...',
            ];
        }
        return $data;
    }
}
