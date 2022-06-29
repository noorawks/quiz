@extends('layout.skeleton')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5>Congratulation, You finished the quiz!</h5>
            <h5>And the result score is <b>{{ $result->total_points }}</b></h5>
        </div>
    </div>
@endsection