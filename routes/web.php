<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\Admin\GroupController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::name('admin.')->namespace('Admin')->group(function (){
    Route::any('admin', 'MainController@login')->name("login");

    Route::group(['prefix' => 'admin','middleware'=>'webCheck'],function () {
        Route::get('main', 'MainController@main')->name("main");
        Route::any('setting', 'MainController@setting')->name("setting");
        Route::get('out', 'MainController@out')->name("out");

        Route::get('comments', 'MainController@getComments')->name('comments');
        Route::get('comment/{id}', 'MainController@acceptComment')->name('accept');

        Route::name('course.')->prefix('course')->group(function (){
            Route::get('/', 'CourseController@index')->name('index');
            Route::get('create', 'CourseController@create')->name('create');
            Route::post('store', 'CourseController@store')->name('store');
            Route::get('edit/{id}', 'CourseController@edit')->name('edit');
            Route::post('update/{id}', 'CourseController@update')->name('update');
            Route::get('destroy/{id}', 'CourseController@destroy')->name('destroy');
            Route::get('users/{id}', 'CourseController@users')->name('users');
            Route::get('usersAdd/{id}', 'CourseController@usersAdd')->name('usersAdd');
            Route::get('usersDelete/{id}', 'CourseController@usersDelete')->name('usersDelete');
            Route::any('usersImport', 'CourseController@usersImport')->name('usersImport');
        });

        Route::name('category_course.')->prefix('category_course')->group(function () {
            Route::get('/{id}', 'CourseCategoryController@index')->name('index');
            Route::get('create/{id}', 'CourseCategoryController@create')->name('create');
            Route::get('edit/{id}', 'CourseCategoryController@edit')->name('edit');
            Route::post('store/{id}', 'CourseCategoryController@store')->name('store');
            Route::post('update/{id}', 'CourseCategoryController@update')->name('update');
            Route::get('destroy/{id}', 'CourseCategoryController@destroy')->name('destroy');
            Route::get('befirst/{id}', 'CourseCategoryController@beFirst')->name('beFirst');
        });

        Route::name('lesson.')->prefix('lesson')->group(function (){
            Route::get('{course}', 'LessonController@index')->name('index');
            Route::get('create/{id}', 'LessonController@create')->name('create');
            Route::post('store/{id}', 'LessonController@store')->name('store');
            Route::get('edit/{id}', 'LessonController@edit')->name('edit');
            Route::post('update/{id}', 'LessonController@update')->name('update');
            Route::get('destroy/{id}', 'LessonController@destroy')->name('destroy');
            Route::get('pdfDelete/{id}', 'LessonController@pdfDelete')->name('pdfDelete');
            Route::get('hidden/{id}', 'LessonController@hidden')->name('hidden');
            Route::get('comment/{id}', 'LessonController@comment')->name('comment');
            Route::get('befirst/{id}', 'LessonController@beFirst')->name('beFirst');
        });

        Route::name('user.')->prefix('user')->group(function (){
            Route::get('/', 'UserController@index')->name('index');
            Route::get('create', 'UserController@create')->name('create');
            Route::post('store', 'UserController@store')->name('store');
            Route::get('edit/{id}', 'UserController@edit')->name('edit');
            Route::post('update/{id}', 'UserController@update')->name('update');
            Route::get('destroy/{id}', 'UserController@destroy')->name('destroy');
            Route::get('updateDevice/{id}', 'UserController@updateDevice')->name('updateDevice');
            Route::any('import', 'UserController@import')->name('import');
            Route::get('deleteAll', 'UserController@deleteAll')->name('deleteAll');
            Route::get('deleteCheckbox', 'UserController@deleteCheckbox')->name('deleteCheckbox');
            Route::get('process/{id}', 'UserController@getProcess')->name('process');
            Route::get('lessons/{id}', 'UserController@getLessons')->name('userLessons');
        });

        Route::name('group.')->prefix('group')->group(function() {
            Route::get('/',                     [GroupController::class, 'index']);
            Route::get('create',                [GroupController::class, 'create'])->name('create');
            Route::get('delete/{id}',           [GroupController::class, 'delete'])->name('delete');
            Route::get('delete-user/{group_id}', [GroupController::class, 'deleteUser'])->name('student-delete');
            Route::get('add-user/{group_id}',   [GroupController::class, 'addUser'])->name('student-add');
            Route::post('load-user/{group_id}', [GroupController::class, 'loadUser'])->name('load-user');
            Route::get('add-group',             [GroupController::class, 'addGroup'])->name('add-group');
            Route::get('add-group-all',         [GroupController::class, 'addGroupAll'])->name('add-group-all');
            Route::get('students/{id}',         [GroupController::class, 'getStudent'])->name('students');
            Route::get('lessons/{category}/{group}', [GroupController::class, 'getLesson'])->name('lessons');
            Route::get('categories/{id}',       [GroupController::class, 'getCategory'])->name('categories');
            Route::get('hidden/{id}/{group}',   [GroupController::class, 'hidden'])->name('hidden');
            Route::get('comment/{id}/{group}',  [GroupController::class, 'comment'])->name('comment');
        });

        Route::name('post.')->prefix('post')->group(function (){
            Route::get('/', 'PostController@index')->name('index');
            Route::get('create', 'PostController@create')->name('create');
            Route::post('store', 'PostController@store')->name('store');
            Route::get('edit/{id}', 'PostController@edit')->name('edit');
            Route::post('update/{id}', 'PostController@update')->name('update');
            Route::get('destroy/{id}', 'PostController@destroy')->name('destroy');
        });

        Route::name('question-answer.')->prefix('question-answer')->group(function (){
            Route::get('/', 'QuestionAnswerController@index')->name('index');
            Route::get('create', 'QuestionAnswerController@create')->name('create');
            Route::post('store', 'QuestionAnswerController@store')->name('store');
            Route::get('edit/{id}', 'QuestionAnswerController@edit')->name('edit');
            Route::post('update/{id}', 'QuestionAnswerController@update')->name('update');
            Route::get('destroy/{id}', 'QuestionAnswerController@destroy')->name('destroy');
        });

        Route::name('video.')->prefix('video')->group(function (){
            Route::get('/', 'VideoController@index')->name('index');
            Route::post('store', 'VideoController@store')->name('store');
            Route::post('destroy', 'VideoController@destroy')->name('destroy');
        });
    });
});
