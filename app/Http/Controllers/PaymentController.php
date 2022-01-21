<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Course;
use App\Models\Payment;
use App\Models\User;
use App\Models\UserCourse;
use App\Models\UserLesson;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\ArrayToXml\ArrayToXml;

class PaymentController extends Controller
{
    public function PayboxResult(Request $request)
    {
        Storage::put('payment-'.Carbon::now()->format('Y-m-d').'.log', $request->all());
        if($request['pg_result']) {
            $p = Payment::find($request['pg_order_id']);
            if ($p){
                $uc = new UserCourse();
                $uc->user_id = $p->user_id;
                $uc->course_id = $p->course_id;
                $uc->save();

                $p->status = 'success';
                $p->save();

                $lessons = Lesson::where('course_id', $request['course_id'])->get()->toArray();
                foreach ($lessons as $lesson) {
                    $userLesson = new UserLesson();
                    $userLesson->user_id = $p->user_id;
                    $userLesson->course_id = $request['course_id'];
                    $userLesson->lesson_id = $lesson['id'];
                    $userLesson->isOpened = false;
                    $userLesson->opened = null;
                    $userLesson->time = 0;
                    $userLesson->save();
                }

                $xml = [
                    'response' => [
                        'pg_salt'=> $p->pg_salt,
                        'pg_status'=> 'ok',
                        'pg_description'=> 'test',
                        'pg_sig'=> $p->pg_sig,
                    ]
                ];
            }else{

                $xml = [
                    'response' => [
                        'pg_salt'=> $p->pg_salt,
                        'pg_status'=> 'error',
                        'pg_description'=> 'error',
                        'pg_sig'=> $p->pg_sig,
                    ]
                ];
            }

            return ArrayToXml::convert($xml);
        } else {
            return 'false';
        }
    }
    public function PayboxSuccess(){
        return '<h2>Удачно оплачено</h2>';
    }
    public function PayboxFail(){
        return '<h2>Оплата не прошла</h2>';
    }


}


/*
 * Тестовые карты:

Имя любое латиницей


4405 6450 0000 6150    09-2025     653

5483 1850 0000 0293    09-2025     343

3775 1450 0009 951     09-2025     3446


3D cards:

4405645000006371  12-0216   292  test1

4405645000006374  12-2016   292  test1

4003035000005378  12-2025   323  secure1

5101450000007898  12-2025   454  Master1

 */
