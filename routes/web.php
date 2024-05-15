<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GradebookController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\Course\CourseController;
use App\Http\Controllers\Users\StudentController;
use App\Http\Controllers\Course\ChapterController;
use App\Http\Controllers\CourseStudentQuizController;
use App\Http\Controllers\Course\ChapterQuizController;
use App\Http\Controllers\Course\ChapterUnitController;
use App\Http\Controllers\Course\CoursePreviewController;
use App\Http\Controllers\Course\CourseStudentController;
use App\Http\Controllers\Institution\ScheduleController;
use App\Http\Controllers\Course\ChapterQuizQuestionController;

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

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::group(['middleware' => 'auth'], function () {

    Route::group(['middleware' => ['role:admin']], function () {

        Route::resource('categories', CategoryController::class, [
            'names' => [
                'index'     => 'categories',
                'create'    => 'categories.add',
                'store'     => 'categories.store',
                'update'    => 'categories.update',
                'delete'    => 'categories.delete'
            ]
        ]);

        Route::resource('institutions', InstitutionController::class, [
            'names' => [
                'index'     => 'institutions',
                'create'    => 'institutions.add',
                'store'     => 'institutions.store',
                'update'    => 'institutions.update',
                'delete'    => 'institutions.delete'
            ]
        ]);

        Route::resource('institutions.schedules', ScheduleController::class, [
            'names' => [
                'index'     => 'institutions.schedules',
                'create'    => 'institutions.schedules.add',
                'store'     => 'institutions.schedules.store',
                'update'    => 'institutions.schedules.update',
                'delete'    => 'institutions.schedules.delete'
            ]
        ]);

        Route::resource('users', UserController::class, [
            'names' => [
                'index'     => 'users',
                'create'    => 'users.add',
                'store'     => 'users.store',
                'update'    => 'users.update',
                'delete'    => 'users.delete'
            ]
        ]);

        Route::get('/students/batch', [StudentController::class, 'batch'])->name('students.batch');

        Route::resource('students', StudentController::class, [
            'names' => [
                'index'     => 'students',
                'create'    => 'students.add',
                'store'     => 'students.store',
                'update'    => 'students.update',
                'delete'    => 'students.delete'
            ]
        ]);
    });

    Route::resource('courses.preview', CoursePreviewController::class, [
        'names' => [
            'index'     => 'courses.preview',
        ]
    ]);

    Route::resource('events', EventController::class);

    Route::resource('activities', ActivityController::class, [
        'names' => [
            'index'     => 'activities',
            'create'    => 'activities.add',
            'store'     => 'activities.store',
            'update'    => 'activities.update',
            'delete'    => 'activities.delete'
        ]
    ]);

    Route::post('/image', [ImageController::class, 'store'])->name('image.store');

    Route::resource('courses', CourseController::class, [
        'names' => [
            'index'     => 'courses',
            'create'    => 'courses.add',
            'store'     => 'courses.store',
            'show'      => 'courses.show',
            'update'    => 'courses.update',
            'delete'    => 'courses.delete'
        ]
    ]);

    Route::resource('courses.students', CourseStudentController::class, [
        'names' => [
            'index'     => 'courses.students',
            'create'    => 'courses.students.add',
            'store'     => 'courses.students.store',
            'show'      => 'courses.students.show',
            'update'    => 'courses.students.update',
            'delete'    => 'courses.students.delete'
        ]
    ]);

    Route::resource('courses.students.quiz', CourseStudentQuizController::class, [
        'names' => [
            'show'      => 'courses.students.quiz.show',
            'update'    => 'courses.students.quiz.update',
            'delete'    => 'courses.students.quiz.delete'
        ]
    ]);

    Route::resource('courses.chapters', ChapterController::class, [
        'names' => [
            'index'     => 'courses.chapters',
            'create'    => 'courses.chapters.add',
            'store'     => 'courses.chapters.store',
            'show'      => 'courses.chapters.show',
            'update'    => 'courses.chapters.update',
            'delete'    => 'courses.chapters.delete'
        ]
    ]);

    Route::resource('courses.chapters.units', ChapterUnitController::class, [
        'names' => [
            'index'     => 'courses.chapters.units',
            'create'    => 'courses.chapters.units.add',
            'store'     => 'courses.chapters.units.store',
            'show'      => 'courses.chapters.units.show',
            'update'    => 'courses.chapters.units.update',
            'delete'    => 'courses.chapters.units.delete'
        ]
    ]);

    Route::resource('courses.chapters.quiz', ChapterQuizController::class, [
        'names' => [
            'index'     => 'courses.chapters.quiz',
            'create'    => 'courses.chapters.quiz.add',
            'store'     => 'courses.chapters.quiz.store',
            'show'      => 'courses.chapters.quiz.show',
            'update'    => 'courses.chapters.quiz.update',
            'delete'    => 'courses.chapters.quiz.delete'
        ]
    ]);

    Route::resource('courses.chapters.quiz.questions', ChapterQuizQuestionController::class, [
        'names' => [
            'index'     => 'courses.chapters.quiz.questions',
            'create'    => 'courses.chapters.quiz.questions.add',
            'store'     => 'courses.chapters.quiz.questions.store',
            'show'      => 'courses.chapters.quiz.questions.show',
            'update'    => 'courses.chapters.quiz.questions.update',
            'delete'    => 'courses.chapters.quiz.questions.delete'
        ]
    ]);

    Route::resource('announcements', AnnouncementController::class, [
        'names' => [
            'index'     => 'announcements',
            'create'    => 'announcements.add',
            'store'     => 'announcements.store',
            'show'      => 'announcements.show',
            'update'    => 'announcements.update',
            'delete'    => 'announcements.delete'
        ]
    ]);

    Route::get('/records/students/{student}/quiz/{quiz}',  [RecordController::class, 'index']);

    Route::get('/students/{student}/gradebook', [GradebookController::class, 'index']);

    Route::get('/analytics', [AnalyticsController::class, 'index']);

});

Route::get('/test', function () {
    echo "<img href='" . Storage::url('303/image.png') . "'>";
    echo "<br>";
    echo "<img href='" . asset('storage/303/image.png') . "'>";

    echo "<a href='" . asset('css/app.css') . "'>Click Here</a>";
});
