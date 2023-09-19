@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Tags') }}
    </h2>
@endsection
<!-- START DATA -->
@extends('layouts.templatecss')
@section('content')
    <div class="my-3 p-3 bg-body rounded shadow-sm">
        <!-- FORM PENCARIAN -->
        <div class="pb-3">
            <form class="d-flex" action="{{ url('tags') }}" method="get">
                <input class="form-control me-1" type="search" name="keywords" value="{{ Request::get('keywords') }}"
                    placeholder="Enter keywords" aria-label="Search">
                <button class="btn btn-secondary" type="submit">Serach</button>
            </form>
        </div>

        <!-- TOMBOL TAMBAH DATA -->
        <div class="pb-3">
            <a href='{{ 'tags/create'}}' class="btn btn-primary">+ Add Data</a>
        </div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="col-md-1">No</th>
                    <th class="col-md-4">Name</th>
                    <th class="col-md-4">Description</th>
                    {{-- <th class="col-md-2">Role</th> --}}
                    <th class="col-md-2">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $i =$data->firstItem()?>
                @foreach ($data as $item)
                <tr>
                    <td><?php echo $i?></td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->description }}</td>
                    {{-- <td>{{ $item->role }}</td> --}}
                    <td>
                        <a href='{{ url('tags/'.$item->id.'/edit') }}' class="btn btn-warning btn-sm">Edit</a>
                        <form onsubmit="return confirm('Are you sure to delete this data?')" class='d-inline' action="{{ url('tags/'.$item->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" name="submit" class="btn btn-danger btn-sm">Del</button>
                        </form>
                    </td>
                </tr>
                <?php $i++?>
                @endforeach
            </tbody>
        </table>
    {{ $data->links() }}

    </div>
    <!-- AKHIR DATA -->
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous">
    </script>
    </body>
@endsection
