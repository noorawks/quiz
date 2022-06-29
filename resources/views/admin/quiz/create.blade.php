@extends('layout.admin.master')

@section('custom-css')
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/summernote/summernote-bs4.min.css') }}">
@endsection

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
                <div class="col-8">
                    <div class="card card-primary">
                        <form class="form-horizontal" autocomplete="off" action="{{ route('admin.quiz.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" value="{{ old('name') }}" name="name" class="form-control" placeholder="name" required>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea name="description" required>{{ old('description') }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Duration</label>
                                    <input type="text" value="{{ old('duration') }}" name="duration" class="form-control" placeholder="00:30:00" required>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="{{ route('admin.quiz.index') }}">
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