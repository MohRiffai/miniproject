@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Manage Role') }}
    </h2>
@endsection
<!-- START DATA -->
@extends('layouts.templatecss')
@section('content')
    <a href=" {{ url()->previous() }}" class="my-3 btn btn-secondary">
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
                                            @if (Auth::user()->hasRole('Admin') || $model->name !== 'Admin')
                                                <option value="{{ $model->id }}">{{ $model->name }}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    {{-- <div class="form-group mt-3">
                                        <label for="role_id">Select Role:</label>
                                        <select class="form-control" name="role_id" id="role_id">
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div> --}}
                                    <div class="form-group mt-3">
                                        <label for="role_id">Select Role:</label>
                                        <select class="form-control" name="role_id" id="role_id">
                                            @foreach ($roles as $role)
                                                @if (Auth::user()->hasRole('Admin') || $role->name !== 'Admin')
                                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-3">Assign Role</button>
                                </form>
                                <div class="mt-4">
                                    <h4>Roles Assigned to Users:</h4>
                                    <p style="color: red; font-size: 14px; font-style: italic;">(Klik untuk menghapus Role
                                        pada User)</p>

                                    <ul class="list-inline">
                                        @foreach ($modelHasRoles as $modelHasRole)
                                            @if (Auth::user()->hasRole('Admin') || $modelHasRole->role_name !== 'Admin')
                                                <li class="list-inline-item">
                                                    <form
                                                        onsubmit="return confirm('Are you sure to revoke this role from the user?')"
                                                        class="d-inline"
                                                        action="{{ route('remove.role', ['user_name' => $modelHasRole->user_name, 'role_name' => $modelHasRole->role_name]) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm mt-3"
                                                            style="background-color: red; color: white; border: none; border-radius: 5px; padding: 5px 10px;">{{ $modelHasRole->user_name }}
                                                            => {{ $modelHasRole->role_name }}</button>
                                                    </form>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">Assign Permission to Role</div>
                            <div class="card-body">
                                <!-- Form to assign a permission to a role -->
                                <form action="{{ route('roles.givePermission') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="role_id">Select Role:</label>
                                        <select class="form-control" name="role_id" id="role_id">
                                            @foreach ($roles as $role)
                                                @if (Auth::user()->hasRole('Admin') || $role->name !== 'Admin')
                                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group mt-3">
                                        <label for="permission_id">Select Permission:</label>
                                        <select class="form-control" name="permission_id" id="permission_id">
                                            @foreach ($permissions as $permission)
                                                <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-3">Assign Permission</button>
                                </form>
                                @php
                                    $currentRole = null;
                                    $sortedRoleHasPermissions = $roleHasPermissions->sortBy('role_name');
                                @endphp

                                <div class="mt-4">
                                    <h4>Permission Assigned to Role:</h4>
                                    <p style="color: red; font-size: 14px; font-style: italic;">(Klik "Detach" untuk
                                        menghapus Permission pada)</p>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Role</th>
                                                <th>Permission</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($sortedRoleHasPermissions as $roleHasPermission)
                                                @if (Auth::user()->hasRole('Admin') || $roleHasPermission->role_name !== 'Admin')
                                                    @if ($currentRole !== $roleHasPermission->role_name)
                                                        @php
                                                            $currentRole = $roleHasPermission->role_name;
                                                        @endphp
                                                        <tr>
                                                            <td colspan="3">
                                                                <strong>{{ $roleHasPermission->role_name }}</strong>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    <tr>
                                                        <td></td>
                                                        <td>{{ $roleHasPermission->permission_name }}</td>
                                                        <td>
                                                            <form
                                                                onsubmit="return confirm('Are you sure to revoke this permission from the role?')"
                                                                class="d-inline"
                                                                action="{{ route('roles.permissions.revoke', [$roleHasPermission->role_name, $roleHasPermission->permission_name]) }}"
                                                                method="post">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm"
                                                                    style="background-color: red; color: white; border: none; border-radius: 5px; padding: 5px 10px;">Detach</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
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
