@extends('layout.admin.master')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Quiz</h1>
                </div>
            </div>

            @include('admin.part.error-message')
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-6">
                    <div class="card card-primary">
                        <form class="form-horizontal" autocomplete="off" action="{{ route('admin.question.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Question</label>
                                    <textarea name="question_text" class="form-control" rows="10">{{ old('question_text') }}</textarea>
                                </div>
                                <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="{{ route('admin.quiz.show', $quiz->id) }}">
                                    <button type="button" class="btn btn-default">Cancel</button>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->

@endsection