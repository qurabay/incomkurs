<?php

namespace App\Console\Commands;

use App\Models\Course;
use App\Models\PostCat;
use Carbon\Carbon;
use Illuminate\Console\Command;

class everyMinute extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'minute:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create Post Cat';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
//        \App\Models\UserCourse::whereRaw("courses.deadline < NOW()")
//            ->join('courses','courses.id','user_courses.course_id')
//            ->select('courses.deadline')
//            ->delete();
    }
}
