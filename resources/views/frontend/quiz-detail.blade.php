@extends('layout.skeleton')

@section('content')
    <div class="card">
        <div class="card-body" style="text-align: center;">
            <h5>{{ $quiz->name }}</h5>
            <p>You will have {{ $quiz->name }} Quiz for {{ $quiz->duration }} Minute</p>
            <p>{!! $quiz->description !!}</p>
            <p>Click button below to start quiz</p>
            <a href="{{ route('quiz-ongoing', $quiz->id) }}">
                <button class="btn btn-default">Start</button>
            </a>
        </div>
    </div>
@endsection