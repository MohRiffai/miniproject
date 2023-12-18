@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Role') }}
    </h2>
@endsection
{{-- <head>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
                integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
        </head>
        <body class="bg-light">
            <main class="container"> --}}
<!-- START DATA -->
@extends('layouts.templatecss')
@section('content')
    {{-- <div class="my-3 p-3 bg-body rounded shadow-sm">
        <!-- FORM PENCARIAN -->
        <div class="pb-3">
            <form class="d-flex" action="{{ url('roles') }}" method="get">
                <input class="form-control me-1" type="search" name="keywords" value="{{ Request::get('keywords') }}"
                    placeholder="Enter keywords" aria-label="Search">
                <button class="btn btn-secondary" type="submit">Serach</button>
            </form>
        </div>

        <!-- TOMBOL TAMBAH DATA -->
        <div class="pb-3" style="display: flex; justify-content: space-between;">
            <a href="{{ route('roles.create') }}" class="btn btn-primary">+ Add Data</a>
            <a href="{{ route('roles.manage') }}" class="btn btn-primary">Manage Role</a>
        </div>



        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="col-md-1">No</th>
                    <th class="col-md-4">Name</th>
                    <th class="col-md-4">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php //$i = $data->firstItem();
                ?>
                @foreach ($data as $item)
                    <tr>
                        <td><?php //echo $i;
                        ?></td>
                        <td>{{ $item->name }}</td>
                        <td>
                            <a href='{{ url('roles/' . $item->id . '/edit') }}' class="btn btn-warning btn-sm">Edit</a>
                            <form onsubmit="return confirm('Are you sure to delete this data?')" class='d-inline'
                                action="{{ url('roles/' . $item->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" name="submit" class="btn btn-danger btn-sm">Del</button>
                            </form>
                        </td>
                    </tr>
                    <?php //$i++;
                    ?>
                @endforeach
            </tbody>
        </table>
        {{ $data->links() }} 

    </div> --}}
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Role Management</div>
                    <div class="card-body">
                        <!-- Form untuk mencari role -->
                        <form action="{{ route('roles.index') }}" method="GET">
                            <div class="input-group">
                                <input type="text" name="keywords" class="form-control" placeholder="Search by Name">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary ml-3">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Existing Roles</div>
                    <div class="card-body">
                        <div class="pb-3" style="display: flex; justify-content: space-between;">
                            <a href="{{ route('roles.create') }}" class="btn btn-success">+ Add Data</a>
                            <a href="{{ route('roles.manage') }}" class="btn btn-primary">Manage Role</a>
                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Guard Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $role)
                                    <tr>
                                        @if (Auth::user()->hasRole('Admin') || $role->name !== 'Admin')
                                            <td>{{ $role->name }}</td>
                                            <td>{{ $role->guard_name }}</td>
                                        @endif
                                        <td>
                                            @if (Auth::user()->hasRole('Admin') || $role->name !== 'Admin')
                                                <a href='{{ url('roles/' . $role->id . '/edit') }}'
                                                    class="btn btn-warning btn-sm">Edit</a>
                                                <form onsubmit="return confirm('Are you sure to delete this data?')"
                                                    class='d-inline' action="{{ url('roles/' . $role->id) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" name="submit"
                                                        class="btn btn-danger btn-sm">Del</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
