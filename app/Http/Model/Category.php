<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';
    protected $primaryKey = 'cate_id';
    public $timestamps = false;
    protected $guarded = [];

    // 获取分类
    public function cate_list(){
        $category = $this->orderBy('cate_order','asc')->get();
        return $this->getTree($category,'cate_name','cate_id','cate_pid',$pid=0);
    }

    // 树形分类
    public function getTree($data,$field_name,$field_id='id',$field_pid='pid',$pid=0){
        $tree = array();
        foreach($data as $k => $v){
            if($v->$field_pid == $pid){
                $tree[] = $data[$k];
                $data[$k]['_'.$field_name] = $data[$k][$field_name];
                foreach($data as $m => $n){
                    if($n->$field_pid == $v->$field_id){
                        $data[$m]['_'.$field_name] = '┣━ '.$data[$m][$field_name];
                        $tree[] = $data[$m];
                    }
                }
            }
        }
        return $tree;
    }
}
