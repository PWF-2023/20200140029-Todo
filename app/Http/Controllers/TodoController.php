<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index()
    {
        $todos = Todo::where('user_id', auth()->user()->id)
            ->orderBy('is_complete', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        //dd($todos);
        return view('todo.index', compact('todos'));
    }

    public function store(Request $request, Todo $todo)
    {
        $request->validate([
            'title' => 'required|max:255',
        ]);

        //Practical
        //$todo = new Todo;
        //$todo->title = $request->title;
        //$todo->user_id = auth()->user()->id;
        //$todo->save();

        //Query Builder Way
        //DB::table('todos')->insert([
        //'title' => $request->title,
        //'user_id' => auth()->user()->id,
        //'created_at' => now(),
        //'updated_at' => now(),
        //])
        $todo = Todo::create([
            'title' => ucfirst($request->title),
            'user_id' => auth()->user()->id,
        ]);
        return redirect()->route('todo.index')->with('success', 'Todo created successfully!');
    }

    public function create()
    {
        return view('todo.create');
    }

    public function edit()
    {
        return view('todo.edit');
    }
}