@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Create new article') }}
    </h2>
@endsection
@extends('layouts.templatecss')
@section('content')
    <!-- START FORM -->
    <a href="{{ url('articles') }}" class="my-3 btn btn-secondary"><< Back</a>
    <div class="col-lg-8">
        <form action='{{ url('aritcles') }}' method='post'>
            @csrf
            <div class="my-3 p-3 bg-body rounded shadow-sm">
                <div class="mb-3 row">
                    <label for="tittle" class="col-sm-2 col-form-label">Tittle</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name='tittle' id="tittle">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="slug" class="col-sm-2 col-form-label">Slug</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name='slug' id="slug">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="category" class="col-sm-2 col-form-label">Category</label>
                    <div class="col-sm-10">
                        <select class="form-select" name="category_id" id="">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="content" class="col-sm-2 col-form-label">Content</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name='content' id="content">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="save" class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-10"><button type="submit" class="btn btn-primary" name="submit">Create Post</button>
                    </div>
                </div>
        </form>
    </div>
    <!-- AKHIR FORM -->
<script>
    const tittle = document.querySelector('#tittle');
    const slug = document.querySelector('#slug');

    tittle.addEventListener('change', function(){
        fetch('/articles/article/checkSlug?tittle=' + tittle.value)
            .then(response => response.json())
            .then(data => slug.value = data.slug)
    });
</script>
@endsection
