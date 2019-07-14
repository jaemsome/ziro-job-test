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

Auth::routes();

Route::get('/test', function(){
    // $new_task = new App\Task();
    // $new_task->name = 'dsadada';
    // $new_task->status = 1;
    // $new_task->archived = 0;
    // $new_task->owner = 'James';

    $user = App\User::find(Auth::user()->id);

    // $task = [
    //     'name'       => 'dsadasda',
    //     'status'     => 2,
    //     'archived'   => 0,
    //     'owner'      => 'James',
    //     'created_at' => Carbon\Carbon::now()->diffForHumans(),
    //     'updated_at' => Carbon\Carbon::now()->diffForHumans()
    // ];

    // $user->tasks()->save(new App\Task($task));

    if($resp = $user->tasks()->detach(6)) {
    }

    return response()->json(['response' => $resp], 200);
});

Route::get('/', 'HomeController@index')->name('home');

Route::get('/tasks', 'TaskController@index')->name('tasks.index');
Route::resource('/task', 'TaskController')->except(['index', 'show', 'create', 'edit']);

Route::get('/task/statuses', 'TaskStatusController@index');
