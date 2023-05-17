<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index()
{
        $todos = Todo::where('user_id',auth()->user()->id)
            ->orderBy('is_complete','asc')
            ->orderBy('created_at','desc')
            ->get();
        // dd($todos);
        // dd($todos->toArray());
        $todosCompleted = Todo::where('user_id',auth()->user()->id)
            ->where('is_complete',true)
            ->count();
        return view('todo.index',compact('todos','todosCompleted'));
    }

    public function store(Request $request,Todo $todo)
    {
        $request->validate([
            'title'=>'required|max:255',
        ]);

        $todo = Todo::create([
            'title'=>ucfirst($request->title),
            'user_id'=> auth()->user()->id,
        ]);
        return redirect()->route('todo.index')->with('success','Todo created successfully!');
    }

    public function create()
    {
        return view('todo.create');
    }

    public function edit(Todo $todo)
    {
        if(auth()->user()->id = $todo->user_id){
            return view('todo.edit',compact('todo'));
        }
        return redirect()->route('todo.index')->with('danger','You are not authorized to edit this todo!');

    }

    public function update(Request $request,Todo $todo)
    {
        $request->validate([
                'title' => 'required|max:255',
            ]);
        $todo->update([
            'title'=>ucfirst($request->title),
        ]);
        return redirect()->route('todo.index')->with('success','Todo updated successfully!');
    }

    public function complete(Todo $todo)
    {
        if(auth()->user()->id == $todo->user_id){
            $todo->update([
                'is_complete'=>true,
            ]);
            return redirect()->route('todo.index')->with('success','Todo completed successfully!');
        }
        return redirect()->route('todo.index')->with('danger','You are not authorized to complete this todo!');

    }

    public function uncomplete(Todo $todo)
    {
        if(auth()->user()->id == $todo->user_id){
            $todo->update([
                'is_complete'=>false,
            ]);
            return redirect()->route('todo.index')->with('success','Todo uncompleted successfully!');
        }
        return redirect()->route('todo.index')->with('danger','You are not authorized to uncomplete this todo!');

    }

    public function destroy(Todo $todo)
    {
        if(auth()->user()->id == $todo->user_id){
            $todo->delete();
            return redirect()->route('todo.index')->with('success','Todo deleted successfully!');
        }
        return redirect()->route('todo.index')->with('danger','You are not authorized to delete this todo!');

    }

    public function destroyCompleted()
    {
        $todoCompleted = Todo::where('user_id',auth()->user()->id)
            ->where('is_complete',true)
            ->get();
        foreach($todoCompleted as $todo){
            $todo->delete();
        }
        return redirect()->route('todo.index')->with('success','All completed todos deleted successfully!');
    }
}