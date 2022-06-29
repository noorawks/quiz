<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Option;
use App\Models\Result;

class FrontEndController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::with(['questions.options'])
                    ->has('questions.options')
                    ->orderByDesc('created_at')
                    ->get();

        return view('frontend.index', compact('quizzes'));
    }

    public function quiz($id)
    {
        $quiz = Quiz::find($id);

        if (!$quiz) {
           return redirect()->back()->withErrors('Quiz not found');
        }

        return view('frontend.quiz-detail', compact('quiz'));
    }

    public function quizOngoing($id)
    {
        $quiz = Quiz::with(['questions.options'])
                    ->has('questions.options')
                    ->find($id);

        if (!$quiz) {
            return redirect()->back()->withErrors('Quiz still incompleted');
         }

        return view('frontend.quiz-ongoing', compact('quiz'));
    }

    public function quizSubmit(Request $request)
    {    
        $emptyAnswer = $request->input('options') ? true : false;

        $result = new Result;
        $result->user_id = Auth::id();
        $result->quiz_id = $request->quiz_id;
        $result->total_points = 0;
        
        if ($emptyAnswer == true) {
            $options = Option::whereIn('id', $request->input('options'))->get();
            $result->total_points = $options->sum('points');
            
            $questions = $options->mapWithKeys(function ($option) {
                return [$option->question_id => [
                    'option_id' => $option->id,
                    'point' => $option->points
                    ]
                ];
            })->toArray();
            
            $result->questions()->sync($questions);
            $result->load('quiz');
        }
        
        $result->save();
        
        return view('frontend.quiz-result', compact('result'));
    }
}
