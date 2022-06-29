<?php

namespace App\Http\Controllers;

use DB;

use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;

use App\Models\Quiz;
use App\Models\Question;

use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;

class QuestionController extends Controller
{
    public function index()
    {

    }

    public function show(Question $question)
    {
        $question->load('options');
        return view('admin.question.show', compact('question'));
    }

    public function create()
    {
        $quiz = Quiz::find(request('quiz_id'));
        
        if (!$quiz) {
            return redirect()->back()->withError('Quiz is not found');
        }

        return view('admin.question.create', compact('quiz'));
    }

    public function store(StoreQuestionRequest $request)
    {
        DB::beginTransaction();

        try {
            Question::create([
                'quiz_id' => $request->quiz_id,
                'question_text' => $request->question_text,
            ]);
            
            DB::commit();

            return redirect()->route('admin.quiz.show', $request->quiz_id)->withSuccess('Question created');
        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->route('admin.quiz.show', $request->quiz_id)->withError('Failed to create question');
        } 
    }

    public function storeBulk(Request $request)
    {
        DB::beginTransaction();

        try {
            $questions = (new FastExcel)->import($request->file('questions'), function ($line) use ($request) {
                $question = Question::firstOrCreate([
                    'quiz_id' => $request->quiz_id,
                    'question_text' => $line['question'],
                ]);

                $question->options()->create([
                    'option_text' => $line['option'],
                    'points' => $line['points'],
                ]);
            });
        
            DB::commit();

            return redirect()->route('admin.quiz.show', $request->quiz_id)->withSuccess('Question created');
        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->route('admin.quiz.show', $request->quiz_id)->withError('Failed to create question');
        } 

    }

    public function edit(Question $question)
    {
        return view('admin.question.edit', compact('question'));
    }

    public function update(UpdateQuestionRequest $request, Question $question)
    {
        DB::beginTransaction();

        try {
            $question->update([
                'question_text' => $request->question_text,
            ]);
            
            DB::commit();

            return redirect()->route('admin.quiz.show', $question->quiz_id)->withSuccess('Question updated');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('admin.quiz.show', $question->quiz_id)->withError('Failed to update question');
        } 
    }

    public function destroy(Question $question)
    {
        if ($question->delete()) {
            return redirect()->back()->withSuccess('Question deleted');
        } else {
            return redirect()->back()->withErrors('Failed to delete question');
        }
    }
}
