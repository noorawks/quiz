<?php

namespace App\Http\Controllers\API;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Quiz;

use App\Http\Resources\QuizResource;
use App\Http\Resources\QuizResourceCollection;


class QuizController extends Controller
{
    public function index(Request $request)
    {
        $search = request('search') ?: null;

        $quizzes = Quiz::when($search, function ($query) use ($search) {
                            return $query->where('name', 'LIKE', '%' . $search . '%');
                        })
                        ->orderByDesc('created_at')
                        ->paginate(5);

        $quizzes->appends($request->except('page'));

        return response()->json([
            new QuizResourceCollection($quizzes),
        ], 201);
    }
}