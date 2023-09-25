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
                        <div class="mb-3 row">
                            <label for="category" class="col-sm-2 col-form-label">Category</label>
                            <div class="col-sm-10">
                                <select class="form-select" name="category_name" id="">
                                    @isset($categories)
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->name }}" {{ (old('category_name', $data->category_name) === $category->name) ? 'selected' : '' }}>
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
                                    value="{{ old('content') }}">
                                <trix-editor input="content"></trix-editor>
                            </div>
                        </div>
                        {{-- <div class="mb-3 row">
                            <label for="tag" class="col-sm-2 col-form-label">Tags</label>
                            <div class="col-sm-10">
                                <select class="form-select" name="tag_id[]" multiple="multiple" id="tag_id">
                                    @foreach ($tags as $tag)
                                        <option value="{{ $tag->id }}" {{ is_array(old('tag_id')) && in_array($tag->id, old('tag_id')) ? 'selected' : '' }}>
                                            {{ $tag->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>                         --}}
                        <div class="mb-3 row">
                            <label for="tag" class="col-sm-2 col-form-label">Tags</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="tag_id[]" multiple="multiple" id="tag_id">
                                    @foreach ($tags as $tag)
                                    {{-- <option {{ isset($article) && $article->tag()->find($tag->id) ? 'selected' : '' }} value="{{ $tag->id }}">{{ $tag->name }}</option>     --}}
                                    <option {{ $article->tag()->find($tag->id) ? 'selected' : '' }} value="{{ $tag->id }}">{{ $tag->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3 row">
                            <label for="save" class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-10"><button type="submit" class="btn btn-primary" name="submit">Edit
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
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>            
            <script>
                $(document).ready(function() {
                    $('#tag_id').select2({
                        placeholder: "Pilih Tag",
                        multiple: true
                    });
                });
            </script>    
        @endsection
