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
                    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
                </head>
                <form action='{{ url('articles/' . $data->slug) }}' method='post' enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="my-3 p-3 bg-body rounded shadow-sm">
                        <div class="mb-3 row">
                            <label for="tittle" class="col-sm-2 col-form-label">Tittle</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('tittle') is-invalid @enderror"
                                    name='tittle' id="tittle" value="{{ old('tittle', $data->tittle) }}">
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
                                    name='slug' id="slug" value="{{ old('slug', $data->slug) }}" readonly>
                                @error('slug')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        {{-- <div class="mb-3 row">
                            <label for="author" class="col-sm-2 col-form-label">Author</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="author" id="author" value="{{ $data->author->name }} ({{ $article->author->roles->first()->name }})">
                            </div>
                        </div> --}}
                        <div class="mb-3 row">
                            <label for="author" class="col-sm-2 col-form-label">Author</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="author_id" id="author_id">
                                    @foreach ($authors as $author)
                                        <option value="{{ $author->id }}" {{ $author->id == $data->author_id ? 'selected' : '' }}>
                                            {{ $author->name }}
                                            @if ($author->roles->isNotEmpty())
                                                ({{ $author->roles->pluck('name')->implode(', ') }})
                                            @else
                                                {{ __('(Belum Mempunyai Role)') }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="category" class="col-sm-2 col-form-label">Category</label>
                            <div class="col-sm-10">
                                <select class="form-select" name="category_name" id="">
                                    @isset($categories)
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->name }}"
                                                {{ old('category_name', $data->category_name) === $category->name ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="image" class="col-sm-2 col-form-label">Post Image</label>
                            <div class="col-sm-10">
                                <input class="form-control @error('image') is-invalid @enderror" type="file"
                                    id="image" name="image">
                                @error('image')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="content" class="col-sm-2 col-form-label">Content</label>
                            <div class="col-sm-10">
                                @error('content')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                                <input id="content" type="hidden" name="content"
                                    value="{{ old('content', $data->content) }}">
                                <trix-editor input="content"></trix-editor>
                            </div>
                        </div>
                        {{-- <div class="mb-3 row">
                            <label for="tag" class="col-sm-2 col-form-label">Tags</label>
                            <div class="col-sm-10">
                                <select class="form-select" name="tag_id[]" multiple="multiple" id="tag_id">
                                    @foreach ($tags as $tag)
                                        <option value="{{ $tag->id }}" {{ (old('tag_id',[] , $data->tag_name) === $tag->name) ? 'selected' : '' }}>
                                            {{ $tag->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div> --}}
                        {{-- <div class="mb-3 row">
                            <label for="tag" class="col-sm-2 col-form-label">Tags</label>
                            <div class="col-sm-10">
                                <select class="form-select" name="tag_id[]" multiple="multiple" id="tag_id">
                                    @foreach ($tags as $tag)
                                        <option value="{{ $tag->id }}"> {{ $tag->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> --}}
                        <div class="mb-3 row">
                            <label for="tag" class="col-sm-2 col-form-label">Tags</label>
                            <div class="col-sm-10">
                                <select class="form-select @error('tag_name') is-invalid @enderror" name="tag_name[]"
                                    multiple="multiple" id="tag_name">
                                    @foreach ($tags as $tag)
                                        <option value="{{ $tag->name }}"
                                            @if (in_array($tag->name, old('tag_name', $selectedTagIds))) selected @endif>
                                            {{ $tag->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tag_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="save" class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-10"><button type="submit" class="btn btn-primary" name="submit">Edit Post</button>
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

            {{-- Script  javascript untuk menonaktifkan dropdown pada Author, apabila user punya role Author dia tidak bisa edit nama Author --}}
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    var userRole = "{{ auth()->user()->getRoleNames()->first() }}";
                    var authorDropdown = document.getElementById('author_id');
            
                    if (userRole === 'Author') {
                        authorDropdown.disabled = true;
                    }
                });
            </script>

            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"
                integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA=="
                crossorigin="anonymous" referrerpolicy="no-referrer"></script>
            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
            <script>
                $(document).ready(function() {
                    $('#tag_name').select2({
                        placeholder: "Pilih Tag",
                        multiple: true
                    });
                });
            </script>
        @endsection
