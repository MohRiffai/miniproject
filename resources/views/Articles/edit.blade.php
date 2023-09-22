@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Edit article') }}
    </h2>
@endsection
@extends('layouts.articlecss')
@section('content')
    <!-- START FORM -->
    <a href="{{ url('articles') }}" class="my-3 btn btn-secondary">
        << Back</a>
            <div class="col-lg-8">

                <head>
                    {{-- Trix Editor --}}
                    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
                    <script type="text/javascript" src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>

                    {{-- Non aktifkan fitur upload file pada Trix Editor  --}}
                    <style>
                        trix-toolbar [data-trix-button-group="file-tools"] {
                            display: none;
                        }
                    </style>
                </head>
                <form action='{{ url('articles/' . $data->slug) }}' method='post'>
                    @csrf
                    @method('PUT')
                    <div class="my-3 p-3 bg-body rounded shadow-sm">
                        <div class="mb-3 row">
                            <label for="tittle" class="col-sm-2 col-form-label">Tittle</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('tittle') is-invalid @enderror"
                                    name='tittle' value="{{ $data->tittle }}" id="tittle" value="{{ old('tittle') }}">
                                @error('tittle')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="slug" class="col-sm-2 col-form-label">Slug</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                    name='slug' value="{{ $data->slug }}" id="slug" value="{{ old('slug') }}">
                                @error('slug')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        {{-- <div class="mb-3 row">
                            <label for="category" class="col-sm-2 col-form-label">Category</label>
                            <div class="col-sm-10">
                                <select class="form-select"name="category_id" id="">
                                    @isset($categories)
                                        @foreach ($categories as $category)
                                            @if (old('category_id') == $category->id)
                                                <option value="{{ $category->id }}" selected>{{ $category->name }}</option>
                                            @else
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endif
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                        </div> --}}
                        <div class="mb-3 row">
                            <label for="category" class="col-sm-2 col-form-label">Category</label>
                            <div class="col-sm-10">
                                <select class="form-select" name="category_id" id="">
                                    @isset($categories)
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" {{ (old('category_id', $data->category_id) == $category->id) ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3 row">
                            <label for="content" class="col-sm-2 col-form-label">Content</label>
                            <div class="col-sm-10">
                                @error('content')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                                <input id="content" type="hidden" name="content" value="{{ $data->content }}"
                                    value="{{ old('body') }}">
                                <trix-editor input="content"></trix-editor>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="save" class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-10"><button type="submit" class="btn btn-primary" name="submit">Create
                                    Post</button>
                            </div>
                        </div>
                </form>
            </div>
            <!-- AKHIR FORM -->
            <script>
                const tittle = document.querySelector('#tittle');
                const slug = document.querySelector('#slug');

                tittle.addEventListener('change', function() {
                    fetch('/articles/article/checkSlug?tittle=' + tittle.value)
                        .then(response => response.json())
                        .then(data => slug.value = data.slug)
                });

                // {{-- Non aktifkan fitur upload file pada Trix Editor  --}}
                document.addEventListener('trix-file-accept', function(e) {
                    e.preventDefault();
                })
            </script>
        @endsection
