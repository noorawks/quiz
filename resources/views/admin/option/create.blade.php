<form id="add-option" class="form-horizontal" autocomplete="off" action="{{ route('admin.option.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="modal-body">
        <div class="form-group">
            <label>Option</label>
            <textarea name="option_text" class="form-control" rows="3">{{ old('option_text') }}</textarea>
        </div>
        <div class="form-group">
            <label>Points</label>
            <input type="text" name="points" class="form-control">
        </div>
        <input type="hidden" name="question_id" value="{{ $question->id }}">
    </div>
    <!-- /.card-body -->

    <div class="modal-footer justify-content-between">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>