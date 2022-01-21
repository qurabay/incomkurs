<?php

namespace App\Http\Controllers\Admin;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Post;
use App\Models\PostCat;
use App\Packages\Firebase;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{

    public function index()
    {
        $data['videos'] = Storage::files('videos');
        if (session()->has('admin')) {
            return view('admin.video.index', $data);
        }
        if (session()->has('moderator')) {
            return view('admin.video-moderator.index', $data);
        }
    }
    public function store(Request $request)
    {
        $rules = [
            'video'=> 'required',
        ];
        $messages = [

        ];
        $validator = $this->validator($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }

        $this->uploadFile($request['video'],'videos');
        if (session()->has('admin')) {
            return redirect()->route('admin.video.index');
        }
        if (session()->has('moderator')) {
            return redirect()->route('admin.video-moderator.index');
        }
    }
    public function destroy(Request $request)
    {
        $this->deleteFile($request['path']);

        return redirect()->back();
    }
}
