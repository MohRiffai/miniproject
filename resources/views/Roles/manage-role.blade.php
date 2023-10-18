@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Manage Role') }}
    </h2>
@endsection
<!-- START DATA -->
@extends('layouts.templatecss')
@section('content')
    <a href="{{ url('roles') }}" class="my-3 btn btn-secondary">
        << Back</a>
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">Assign Role to User</div>
                            <div class="card-body">
                                <!-- Form to assign a role to a user -->
                                <form action="{{ route('role.assign') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="model_id">Select User:</label>
                                        <select class="form-control" name="model_id" id="model_id">
                                            @foreach ($models as $model)
                                                <option value="{{ $model->id }}">{{ $model->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mt-3">
                                        <label for="role_id">Select Role:</label>
                                        <select class="form-control" name="role_id" id="role_id">
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-3">Assign Role</button>
                                </form>
                                <div class="mt-4">
                                    <h4>Roles Assigned to Users:</h4>
                                    <p style="color:red; font-size:14px; font-style:italic ">(Klik untuk menghapus Role pada User)</p>
                                    <ul>
                                        @foreach ($modelHasRoles as $modelHasRole)
                                            <li>
                                                <form
                                                    onsubmit="return confirm('Are you sure to revoke this role from the user?')"
                                                    class="d-inline"
                                                    action="{{ route('remove.role', ['user_name' => $modelHasRole->user_name, 'role_name' => $modelHasRole->role_name]) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm mt-3">Username: {{ $modelHasRole->user_name }} => Role: {{ $modelHasRole->role_name }}</button>
                                                </form>

                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">Manage Role & Permissions</div>
                            <div class="card-body">
                                <h3 class="card-title">Manage Role:</h3>
                                <div class="form-group mt-3">
                                    <label for="role_id">Select Role:</label>
                                    <select class="form-control" name="role_id" id="role_id">
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <h3 class="mb-2 mt-3">Permissions:</h3>
                                <ul class="list-group">
                                    @foreach ($data->permissions as $permission)
                                        <li class="list-group-item">{{ $permission->name }}</li>
                                    @endforeach
                                </ul>

                                <form method="post" action="">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group mt-2">
                                        <label for="permission_ids">Add Permissions:</label>
                                        @foreach ($permissions as $permission)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="permission_ids[]"
                                                    id="permission_{{ $permission->id }}" value="{{ $permission->id }}">
                                                <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                    {{ $permission->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-3">Add Permissions</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- AKHIR DATA -->
            </main>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous">
            </script>
            </body>
        @endsection
