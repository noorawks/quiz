@extends('layout.admin.master')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $user->name }}</h1>
                </div>
                <div class="col-sm-6">
                    <span class="float-sm-right">
                        <a href="{{ route('admin.user.index') }}" class="btn btn-primary">Back</a>
                    </span>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Detail</div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <tbody>
                                    <tr><td>Name</td><td>{{ $user->name }}</td></tr>
                                    <tr><td>Email</td><td>{{ $user->email }}</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST">
                                @csrf
                                @method('delete')
                                <a href="#" v-on:click="getUser('{{ route('admin.user.get-user', $user->id) }}')">
                                    <button type="button" class="btn btn-primary">Edit <i class="fas fa-edit"></i></button>
                                </a>

                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">
                                    Delete <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <img class="justify-content-center" width="50%" src="{{ $user->avatar ?: asset('user.png') }}" alt="">
                </div>
            </div>
        </div>
    </section>
@endsection

@section('modal')
    <div class="modal fade" id="user-edit">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit User</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="edit_user_form" class="form-horizontal" autocomplete="off" :action="url" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('patch')
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input id="edit_user_form_name" name="name" type="text" class="form-control" placeholder="Name" :value="user.name">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input id="edit_user_form_email" name="email" type="text" class="form-control" placeholder="Email" readonly :value="user.email">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Password">
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.17/vue.js"></script>

    <script>
        var app = new Vue({
            el: '.wrapper',
            data: {
                user: [],
                url: '{{ route("admin.user.update", ":id") }}'
            },
            methods: {
                getUser: function (url) {
                    axios.get(url).then( function (response) {
                        app.user = response.data;
                        app.url = app.url.replace(':id', app.user.id);
                        $("#user-edit").modal('show');
                    })
                    .catch( function (error) {
                        console.log(error);
                    });
                }
            }
        });
    </script>
@endsection