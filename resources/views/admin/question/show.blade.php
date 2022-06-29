<div class="card">
    <div class="card-header">
        <button class="btn btn-primary btn-sm add-option" data-question-id="{{ $question->id }}">Add <i class="fa fa-plus"></i></button>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-head-fixed text-nowrap">
            <thead>
                <tr>
                    <th>No </th>
                    <th>Options</th>
                    <th>Points</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($question->options as $key => $option)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $option->option_text }}</td>
                        <td>{{ $option->points }}</td>
                        <td>
                            <form action="{{ route('admin.option.destroy', $option->id) }}" method="POST">
                                <button type="button" class="btn btn-primary edit-option" data-option-id="{{ $option->id }}"><i class="fas fa-edit"></i></button>
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
