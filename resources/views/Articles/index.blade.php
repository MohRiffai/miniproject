@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Articles') }}
    </h2>
@endsection
<!-- START DATA -->
@extends('layouts.articlecss')
@section('content')
    @if (session()->has('success'))
        <div class='alert alert-success' role='alert'>
            {{ session('success') }}
        </div>
    @endif
    <div class="my-3 p-3 bg-body rounded shadow-sm">
        <!-- FORM PENCARIAN -->
        <div class="pb-3">
            <form class="d-flex" action="{{ url('articles') }}" method="get">
                <input class="form-control me-1" type="search" name="keywords" value="{{ Request::get('keywords') }}"
                    placeholder="Enter keywords" aria-label="Search">
                <button class="btn btn-secondary" type="submit">Serach</button>
            </form>
        </div>

        <!-- TOMBOL TAMBAH DATA -->
        <div class="pb-3">
            <a href='{{ 'articles/create' }}' class="btn btn-primary">+ Add Data</a>
        </div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="col-md-1">No</th>
                    <th class="col-md-2">Tittle</th>
                    <th class="col-md-1">Author</th>
                    <th class="col-md-1">Category</th>
                    <th class="col-md-1">Tags</th>
                    <th class="col-md-2">Image</th>
                    {{-- <th class="col-md-2">Role</th> --}}
                    <th class="col-md-2">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = $data->firstItem(); ?>
                @foreach ($data as $item)
                    @if (Auth::user()->hasRole('author') && $item->author_id != Auth::user()->id)
                        <!-- Jika peran pengguna adalah "author" dan artikel tidak dibuat oleh pengguna saat ini, lewati artikel ini -->
                        @continue
                    @endif
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td>{{ $item->tittle }}</td>
                        {{-- <td>{{ $item->author->name }}</td> --}}
                        <td>
                            @if ($item->author)
                                {{ $item->author->name }}
                            @else
                                No Author
                            @endif
                        </td>
                        <td>{{ $item->category_name }}</td>
                        <td>
                            @foreach ($tags as $tag)
                                @if (in_array($tag->name, explode(',', $item->tag_name)))
                                    {{ $tag->name }},
                                @endif
                            @endforeach
                        </td>
                        <td>
                            <img src="{{ asset('storage/' . $item->image) }}" alt="Image"
                                style="max-width: 200px; max-height: 200px;">
                        </td>
                        <td>
                            <a href='' class="btn btn-success btn-sm">View</a>

                            <a href='{{ url('articles/' . $item->slug . '/edit') }}'
                                class="btn btn-warning btn-sm">Edit</a>

                            {{-- @can('update', $article)    
                            <a href='{{ url('articles/' . $item->slug . '/edit') }}' class="btn btn-warning btn-sm">Edit</a>
                            @endcan --}}


                            <form onsubmit="return confirm('Are you sure to delete this data?')" class='d-inline'
                                action="{{ url('articles/' . $item->slug) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" name="submit" class="btn btn-danger btn-sm">Del</button>
                            </form>


                        </td>
                    </tr>
                    <?php $i++; ?>
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
