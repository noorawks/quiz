@extends('layout.admin.master')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Quizzes</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-header">
                            <a href="{{ route('admin.quiz.create') }}">
                                <button class="btn btn-primary btn-sm">Add <i class="fa fa-plus"></i></button>
                            </a>

                            <div class="card-tools">
                                <form method="get" action="">
                                    <div class="input-group input-group-sm" style="width: 150px;">
                                        <input type="text" name="search" class="form-control float-right" placeholder="Search" @if (request('search')) value="{{ request('search') }}" @endif>
    
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-default">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-head-fixed text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Duration</th>
                                        <th>Detail</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($quizzes as $quiz)
                                        <tr>
                                            <td>{{ $quiz->created_at }}</td>
                                            <td>{{ $quiz->name }}</td>
                                            <td>{{ Str::limit($quiz->description, 50) }}</td>
                                            <td>{{ $quiz->duration }}</td>
                                            <td>
                                                <a href="{{ route('admin.quiz.show', $quiz->id) }}">
                                                    <button type="button" class="btn btn-default">View</button>
                                                </a>
                                            </td>
                                            <td>
                                                <form action="{{ route('admin.quiz.destroy', $quiz->id) }}" method="POST">
                                                    <a href="{{ route('admin.quiz.edit', $quiz->id) }}">
                                                        <button type="button" class="btn btn-primary"><i class="fas fa-edit"></i></button>
                                                    </a>
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            {{ $quizzes->appends(request('search'))->links() }}
                        </div>
                        <!-- /.card-footer-->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection