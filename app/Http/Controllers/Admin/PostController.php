<?php

namespace App\Http\Controllers\Admin;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Post;
use App\Models\PostCat;
use App\Packages\Firebase;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostController extends Controller
{

    public function index()
    {
        $data['posts'] = Post::join('post_cats','post_cats.id','posts.cat_id')->select('posts.*','post_cats.name')->orderBy('id','desc')->paginate(15);
        return view('admin.post.index', $data);
    }
    public function create()
    {
        $data['cats'] = PostCat::all();
        $data['new'] = PostCat::find(1);
        return view('admin.post.create',$data);
    }
    public function store(Request $request)
    {
        $rules = [
            'title'=> 'required',
            'cat_id'=> 'required',
        ];
        $messages = [

        ];
        $validator = $this->validator($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }

        $c = new Post();
        $c->title = $request['title'];
        $c->cat_id = $request['cat_id'];
        $c->description = str_replace('<iframe src="//www','<iframe src="https://www',$request['description']);
        if ($request['images']){
            $images = [];
            foreach ($request['images'] as $img){
                $images[] = $this->uploadFile($img);
            }
            $c->images = $images;
        }
        if ($request['videos']){
            $videos = [];
            foreach ($request['videos'] as $url){
                if ($url){
                    $videos[] = $url;
                }
            }
            $c->videos = $videos;
        }
        $c->save();


        Firebase::send('on_new_news',[
            'title' => PostCat::find($c->cat_id)->name,
            'body' => $c->title,
            'sound'=>'default',
            'type'=> 'post',
            'id' => $c->id
        ]);

        return redirect()->route('admin.post.index');
    }

    public function show($id)
    {
        return view('admin.post.show',['lesson'=>Lesson::findOrFail($id)]);
    }
    public function edit($id, Request $request)
    {
        $data['post'] = Post::findOrFail($id);
        $data['cats'] = PostCat::all();
        return view('admin.post.edit',$data);

    }
    public function update($id,Request $request)
    {
        $rules = [
            'title'=> 'required',
            'cat_id'=> 'required',
        ];
        $messages = [

        ];
        $validator = $this->validator($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }

        $c = Post::findOrFail($id);
        $c->title = $request['title'];
        $c->cat_id = $request['cat_id'];
        $c->description = str_replace('<iframe src="//www','<iframe src="https://www',$request['description']);
        if ($request['images']){
            foreach ($c->images as $image) {
                $this->deleteFile($image);
            }

            $images = [];
            foreach ($request['images'] as $img){
                $images[] = $this->uploadFile($img);
            }
            $c->images = $images;
        }

        if ($request['videos']){
            $videos = [];
            foreach ($request['videos'] as $url){
                if ($url){
                    $videos[] = $url;
                }
            }
            $c->videos = $videos;
        }



        $c->save();

        return redirect()->route('admin.post.index');
    }

    public function destroy($id)
    {
        $l = Post::findOrFail($id);
        foreach ($l->images as $image) {
            $this->deleteFile($image);
        }
        $l->delete();
        return redirect()->back();
    }
}
