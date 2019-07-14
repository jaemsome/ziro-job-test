<?php

namespace App\Http\Controllers;

use App\Task;
use App\TaskStatus;
use App\User;
use App\Http\Requests\SaveTaskRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Return all tasks
        // return response()->json( ['tasks' => Auth::user()->tasks()], 200 );
        return response()->json( ['tasks' => User::find(Auth::user()->id)->tasks], 200 );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SaveTaskRequest $request)
    {
        // Save new task record
        $user = User::find(Auth::user()->id);

        $task = [
            'name'      => $request->name,
            'status'    => $request->status,
            'archived'  => $request->archived,
            'owner'     => $user,
            'created_at' => Carbon::now()->diffForHumans(),
            'updated_at' => Carbon::now()->diffForHumans()
        ];

        $new_task = new Task($task);

        $user->tasks()->save($new_task);

        // Replace status and archive to readable format
        $status = TaskStatus::find($task['status']);
        $task['status'] = $status ? ucwords($status->name) : 'Inactive';
        $task['archived'] = $task['archived'] ? 'Yes' : 'No';
        $task['id'] = $new_task->id;

        return response()->json(['task' => $task], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SaveTaskRequest $request, $id)
    {
        // Get selected task record
        $task = Task::find($id);
        $task->name = $request->name;
        $task->status = $request->status;
        $task->archived = $request->archived;
        $task->save();

        return response()->json(['task' => $task], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find(Auth::user()->id);

        foreach($user->tasks as $task) {
            if($resp = $task->whereId($id)->delete()) {
                if($resp = $user->tasks()->detach($id)) {
                    break;
                }
            }
        }

        return response()->json(['deleted' => $resp], 200);
    }
}
