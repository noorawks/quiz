@extends('layout.skeleton')

@section('content')
    <div class="card">
        <div class="card-body">
            <p id="demo" style="font-size:30px; color: red; text-align:center;"></p>
            <form id="quiz-form" method="POST" action="{{ route('quiz-submit') }}">
                @csrf
                <div class="card mb-3">
                    <div class="card-header">{{ $quiz->name }}</div>
    
                    <div class="card-body">
                        @foreach($quiz->questions as $question)
                            <div class="mb-2">
                                <div class="card-header">{{ $question->question_text }}</div>
                                <div class="card-body">
                                    @foreach($question->options as $option)
                                        <div class="form-check">
                                            <input class="form-check-input option-answer" type="radio" name="options[{{ $question->id }}]" value="{{ $option->id }}">
                                            <label class="form-check-label">
                                                {{ $option->option_text }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">

                <div class="form-group row mb-0">
                    <div class="col-md-6">
                        <button id="submit-button" type="submit" class="btn btn-primary">
                            Submit
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    var hms = '{{ $quiz->duration }}';
    var a = hms.split(':');
    var seconds = ( ((+a[0]) * 60 * 60) + ((+a[1]) * 60) + ((+a[2]) / 60) ) * 1000;

    var countDownDate = new Date().getTime() + seconds;

    var x = setInterval(function() {
        var now = new Date().getTime();
            
        var distance = countDownDate - now;
            
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
        document.getElementById("demo").innerHTML = minutes + "m " + seconds + "s ";
            
        if (distance < 0) {
            clearInterval(x);
            document.getElementById("demo").innerHTML = "Quiz is over!!!";
            $('#quiz-form').submit();
        }
    }, 1000);
</script>
@endpush