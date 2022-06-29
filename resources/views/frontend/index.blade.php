@extends('layout.skeleton')

@section('content')
    @guest
        <div class="card">
            <div class="card-body">
                <h5>Welcome,</h5>
                <h3>Please login before join the quiz</h3>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body">
                <h5>Welcome,</h5>
                <p>
                    Choose the quiz below
                </p>
                <table class="table table-sm table-responsive-sm text-center">
                    <thead>
                        <tr>
                            <th>Quiz Name</th>
                            <th>Duration</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($quizzes as $quiz)
                            <tr>
                                <td>{{ $quiz->name }}</td>
                                <td>{{ $quiz->duration }}</td>
                                <td>
                                    <a href="{{ route('quiz', $quiz->id) }}">
                                        <button class="btn btn-sm btn-default">Start</button>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endguest
@endsection