<form id="add-option" class="form-horizontal" autocomplete="off" action="{{ route('admin.option.update', $option->id) }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('patch')
    <div class="modal-body">
        <div class="form-group">
            <label>Option</label>
            <textarea name="option_text" class="form-control" rows="3">{{ old('option_text', $option->option_text) }}</textarea>
        </div>
        <div class="form-group">
            <label>Points</label>
            <input type="text" name="points" class="form-control" value="{{ $option->points }}">
        </div>
    </div>
    <!-- /.card-body -->

    <div class="modal-footer justify-content-between">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>