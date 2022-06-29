<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\Quiz;

use App\Http\Requests\StoreQuizRequest;
use App\Http\Requests\UpdateQuizRequest;

class QuizController extends Controller
{
    public function index()
    {
        $search = request('search') ?: null;

        $quizzes = Quiz::when($search, function ($query) use ($search) {
                            return $query->where('name', 'LIKE', '%' . $search . '%');
                        })
                        ->orderByDesc('created_at')
                        ->paginate(10);

        return view('admin.quiz.index', compact('quizzes'));
    }

    public function create()
    {
        return view('admin.quiz.create');
    }

    public function store(StoreQuizRequest $request)
    {
        DB::beginTransaction();

        try {
            Quiz::create([
                'name' => $request->name,
                'description' => $request->description,
                'duration' => $request->duration,
            ]);
            
            DB::commit();

            return redirect()->route('admin.quiz.index')->withSuccess('Quiz created');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('admin.quiz.index')->withError('Failed to create quiz');
        } 
    }

    public function show(Quiz $quiz)
    {
        $quiz->load('questions');

        return view('admin.quiz.show', compact('quiz'));
    }

    public function showAPI($quiz)
    {
        $quiz = Quiz::find($quiz);
        return $quiz;
    }

    public function edit(Quiz $quiz)
    {
        return view('admin.quiz.edit', compact('quiz'));
    }

    public function update(UpdateQuizRequest $request, Quiz $quiz)
    {
        DB::beginTransaction();

        try {
            $quiz->update([
                'name' => $request->name,
                'description' => $request->description,
                'duration' => $request->duration,
            ]);
            
            DB::commit();

            return redirect()->route('admin.quiz.index')->withSuccess('Quiz updated');
        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->route('admin.quiz.index')->withError('Failed to update quiz');
        } 
    }

    public function destroy(Quiz $quiz)
    {
        if ($quiz->delete()) {
            return redirect()->back()->withSuccess('Quiz deleted');
        } else {
            return redirect()->back()->withErrors('Failed to delete quiz');
        }
    }
}
