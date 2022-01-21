<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('version',function (){
    return [
        'ios'=>1,
        'android'=>1
    ];
});


Route::get('v1/lesson','MainController@Lesson');



Route::prefix('v1')->group(function () {
    Route::get('generator_device_id',function (){
        $id = new \App\Models\GeneratorDeviceId();
        $id->save();
        return $id;
    });

    Route::get('courses','MainController@Courses');
    Route::post('course-buy','MainController@CourseBuy');
    Route::post('login','MainController@Login');
    Route::post('login-by-token','MainController@LoginByToken');

    Route::get('feedback','MainController@Feedback');
    Route::get('question-answers','MainController@QuestionAnswers');

    Route::get('posts','MainController@Posts');
    Route::get('post','MainController@Post');
    Route::get('post-cats','MainController@PostCats');

    Route::get('setting',function (){ return \App\Models\Setting::first();});

    Route::get('lessons','MainController@Lessons');
    Route::middleware(['apiCheck'])->group(function () {
//        Route::get('lesson','MainController@Lesson');
        Route::get('my-courses','MainController@MyCourses');
        Route::get('my-courses2','MainController@MyCourses2');
    });
});

Route::prefix('v2')->group(function () {
    Route::get('get-course','MainController@getCourse')->middleware('userAuth');
    Route::get('lesson','MainController@getLesson')->middleware('userAuth');
    Route::post('open-lesson', 'MainController@openLesson')->middleware('userAuth');
    Route::post('save-time', 'MainController@saveTime')->middleware('userAuth');
    Route::post('get-categories', 'MainController@getCategory');
    Route::post('get-lessons', 'MainController@getLessonByCat');
    Route::post('get-lessons-category', 'MainController@getLessonByCat');

    Route::post('by-category-get-lessons', [\App\Http\Controllers\MainController::class, 'byCategoryLesson'])->middleware('userAuth');
    Route::post('comment', 'MainController@postComment')->middleware('userAuth');
});

//Paybox API
Route::post('paybox/result', 'PaymentController@PayboxResult')->name('PayboxResult');
Route::post('paybox/success', 'PaymentController@PayboxSuccess')->name('PayboxSuccess');
Route::post('paybox/fail', 'PaymentController@PayboxFail')->name('PayboxFail');

