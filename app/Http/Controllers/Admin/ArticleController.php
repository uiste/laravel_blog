<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\Article;
use App\Http\Model\Category;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class ArticleController extends CommonController
{
    // 全部文章列表
    public function index(){
        $data = Article::orderBy('art_id','desc')->paginate(3);
        return view('admin.article.list',compact('data'));
    }

    // 添加文章
    public function create(){
        $cate_model = new Category();
        $data = $cate_model->cate_list();
//        dd($data);
        return view('admin.article.add')->with('data',$data);
    }
    // 添加文章提交方法
    public function store(){
        $input = Input::except('_token');
        $input['art_time'] = strtotime($input['art_time']);
        // 定义验证规则
        $rules = [
            'art_title' => 'required',
            'art_tag'   => 'required',
            'art_description' => 'required',
            'art_thumb' => 'required',
            'art_content' => 'required',
            'art_time'  => 'required',
            'art_editor'=> 'required',
            'cate_id'   => 'required',
        ];
        // 提示信息
        $message = [
            'art_title.required' => '文章名称不能为空',
            'art_tag.required' => '文章关键词不能为空',
            'art_description.required' => '文章描述不能为空',
            'art_thumb.required' => '缩略图不能为空',
            'art_content.required' => '文章内容不能为空',
            'art_time.required' => '创建时间不能为空',
            'art_editor.required' => '文章作者不能为空',
            'cate_id.required' => '文章分类不能为空',
        ];
        $validator = Validator::make($input,$rules,$message);
        if($validator->passes()){//验证规则通过
            $re = Article::create($input);
            if($re){
                return redirect('admin/article');
            }else{
                return back()->with('errors','数据填充失败,稍后请重试...');
            }
        }else{
            return back()->withErrors($validator);
        }
    }
    // 编辑文章
    public function edit($art_id){
        $data = (new Category())->cate_list();
        $field = Article::find($art_id);
        return view('admin.article.edit',compact('data','field'));
    }
    // 更新文章
    public function update($art_id){
        $input = Input::except('_token','_method');
        $input['art_time'] = strtotime($input['art_time']);
        $re = Article::where('art_id',$art_id)->update($input);
        if($re){
            return redirect('admin/article');
        }else{
            return back()->with('errors','文章更新失败,请稍后重试...');
        }
    }
    // 显示单个文章
    public function show(){

    }
    // 删除文章
    public function destroy($art_id){
        $re = Article::where('art_id',$art_id)->delete();
        if($re){
            $data = [
              'status'  =>  0,
              'msg'     => '文章删除成功',
            ];
        }else{
            $data = [
                'status'=>  1,
                'msg'   => '文章删除失败,请稍后重试...',
            ];
        }
        return $data;
    }
}
