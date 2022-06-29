@extends('layout.admin.master')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $quiz->name }}</h1>
                </div>
                <div class="col-sm-6">
                    <span class="float-sm-right">
                        <a href="{{ route('admin.quiz.index') }}" class="btn btn-primary">Back</a>
                    </span>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Detail</div>
                        <div class="card-body">
                            <table class="table table-sm mb-2">
                                <tbody>
                                    <tr><td>Name</td><td>{{ $quiz->name }}</td></tr>
                                    <tr><td>Description</td><td>{!! $quiz->description !!}</td></tr>
                                    <tr><td>Duration</td><td>{{ $quiz->duration }} Minutes</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5>Questions</h5>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-8">
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-header">
                            <a href="{{ route('admin.question.create', ['quiz_id' => $quiz->id]) }}">
                                <button class="btn btn-primary btn-sm">Add <i class="fa fa-plus"></i></button>
                            </a>
                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addBulkQuestion">Add Bulk <i class="fa fa-plus"></i></button>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-head-fixed text-nowrap">
                                <thead>
                                    <tr>
                                        <th>No </th>
                                        <th>Question</th>
                                        <th>Options</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($quiz->questions as $key => $question)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $question->question_text }}</td>
                                            <td>
                                                <a href="{{ route('admin.question.show', $question->id) }}">
                                                    </a>
                                                <button type="button" class="btn btn-default show-options" data-question-id="{{ $question->id }}">View</button>
                                            </td>
                                            <td>
                                                <form action="{{ route('admin.question.destroy', $question->id) }}" method="POST">
                                                    <a href="{{ route('admin.question.edit', $question->id) }}">
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
                    </div>
                    <!-- /.card -->
                </div>
                <div id="get-options" class="col-4">
                </div>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="page"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addBulkQuestion" tabindex="-1" aria-labelledby="addBulkQuestionLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBulkQuestionLabel">Add Bulk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" autocomplete="off" action="{{ route('admin.question.store.bulk') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Upload File</label>
                                <input name="questions" type="file" class="form-control" placeholder="Name">
                            </div>
                            <i>Download Questions Template: <a href="{{ route('admin.download-questions-template') }}">Here</a> </i>
                        </div>
                        <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">
                        <div class="modal-footer justify-content-between">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

<script type="text/javascript">
    $(function() {
        $('.show-options').on('click', function(e) {
            let div = "#get-options";
            let url = '{{ route("admin.question.show", ":id") }}';
            url = url.replace(':id', $(this).data("question-id"));

            showOptions(url, div);
        });

        $('body').on('click', '.add-option', function() {
            let url = '{{ route("admin.option.create") }}?question_id=' + $(this).data("question-id");

            $.get(url, {}, function(data, status) {
                $("#exampleModalLabel").html('Add Option')
                $("#page").html(data);
                $("#exampleModal").modal('show');

            });
        });

        $('body').on('click', '.edit-option', function() {
            let url = '{{ route("admin.option.edit", ":id") }}';
            url = url.replace(':id', $(this).data("option-id"));

            $.get(url, {}, function(data, status) {
                $("#exampleModalLabel").html('Edit Option')
                $("#page").html(data);
                $("#exampleModal").modal('show');

            });
        });

        $('body').on('submit', '#add-option', function() {
            console.log($(this).data("question-id"));
            let url = '{{ route("admin.option.create") }}?question_id=' + $(this).data("question-id");

            $.ajax({
                type: "get",
                url: "{{ url('store') }}",
                data: "name=" + name,
                success: function(data) {
                    $(".btn-close").click();
                    read()
                }
            });
        });

        $('body').on('submit', '#edit-option', function() {
            console.log($(this).data("option-id"));
            let url = '{{ route("admin.option.edit", ":id") }}';
            url = url.replace(':id', $(this).data("option-id"));

            $.ajax({
                type: "get",
                url: "{{ url('store') }}",
                data: "name=" + name,
                success: function(data) {
                    $(".btn-close").click();
                    read()
                }
            });
        });
    });

    function showOptions(url, div)
    {
        $.ajax({
            type: 'GET',
            url: url,
            cache: false,
            dataType: 'HTML',
            success: function (response) {
                $(div).empty().append(response);
            },
            error: function (xhr, status, error) {
                $(div).empty().append(`<font color='red'>`+xhr.statusText+`</font>`);
            }
        });
    }
    
</script>
@endsection