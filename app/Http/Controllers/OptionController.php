<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Option;

use App\Http\Requests\StoreOptionRequest;
use App\Http\Requests\UpdateOptionRequest;

class OptionController extends Controller
{
    public function index()
    {

    }

    public function create()
    {
        $question = Question::find(request('question_id'));
        
        if (!$question) {
            return redirect()->back()->withError('question is not found');
        }

        return view('admin.option.create', compact('question'));
    }

    public function store(StoreOptionRequest $request)
    {
        $question = Question::find(request('question_id'));
        
        if (!$question) {
            return redirect()->back()->withError('question is not found');
        }

        DB::beginTransaction();

        try {
            Option::create([
                'question_id' => $request->question_id,
                'option_text' => $request->option_text,
                'points' => $request->points,
            ]);
            
            DB::commit();

            return redirect()->route('admin.quiz.show', $question->quiz_id)->withSuccess('Option created');
        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->route('admin.quiz.show', $question->quiz_id)->withError('Failed to create option');
        } 

    }

    public function edit(Option $option)
    {
        return view('admin.option.edit', compact('option'));
    }

    public function update(UpdateOptionRequest $request, Option $option)
    {
        DB::beginTransaction();

        try {
            $option->update([
                'option_text' => $request->option_text,
                'points' => $request->points,
            ]);

            $option->load('question');
            
            DB::commit();

            return redirect()->route('admin.quiz.show', $option->question->quiz_id)->withSuccess('Option updated');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('admin.quiz.show', $option->question->quiz_id)->withError('Failed to update option');
        } 
    }

    public function destroy(Option $option)
    {
        if ($option->delete()) {
            return redirect()->back()->withSuccess('Option deleted');
        } else {
            return redirect()->back()->withErrors('Failed to delete option');
        }
    }
}
