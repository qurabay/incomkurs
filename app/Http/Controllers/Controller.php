<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;
use Validator;
use File;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    function validator($errors,$rules,$messages = []) {
        return Validator::make($errors,$rules,$messages);
    }

    protected function uploadFile($file,$dir = 'uploads'){
        if (isset($file)){
            File::isDirectory($dir) or File::makeDirectory($dir, 0777, true, true);

            $file_type = File::extension($file->getClientOriginalName());
            $file_name = time().Str::random(5).'.'.$file_type;
            $file->move($dir, $file_name);
            return $dir.'/'.$file_name;
        }
    }
    protected function deleteFile(string $path){
        if (File::exists($path)) {
            File::delete($path);
            return true;
        }
        else{
            return false;
        }
    }
    function paginate($Model,$Resource,$page = 1,$limit = 15){
        $count = $Model->count();
        $pages = ceil($count / $limit);
        $offset = ($page - 1)  * $limit;

        return [
            'count'=>$count,
            'pages'=>$pages,
            'offset'=>$offset,
            'page'=>(int)$page,
            'data'=> $Resource::collection($Model->offset($offset)->limit($limit)->get())
        ];

    }
}
