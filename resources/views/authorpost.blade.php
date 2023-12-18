@extends('layouts.frontend')

@section('content')

<h4 style="margin-left: 100px; margin-bottom: -80px; margin-top: 50px">Author / {{ $author}}</h4>
    <div class="article-container">
        @foreach ($ArticlesbyAuthor as $item)
        
            <div class="article">
                <title>{{ $author }} - Portal Berita</title>
                <a href="{{ route('singlepost.index', ['slug' => $item->slug]) }}">
                    <img src="{{ asset('storage/' . $item->image) }}" alt="Image">
                </a>
                <h2><a href="{{ route('singlepost.index', ['slug' => $item->slug]) }}">{{ $item->title }}</a></h2>
                <div class="additional-info">
                    <p class="author">{{ $item->author->name }}</p>
                    <p class="timestamp">{{ date('d M, Y', strtotime($item->created_at)) }}</p>
                </div>
                <p>{{ substr(strip_tags($item->content), 0, 150) }}...</p>
                <p class="category">{{ $item->category_name }}</p>
            </div>
        @endforeach
    </div>
    <div class="pagination-links mt-4 d-flex justify-content-center">
        {{ $ArticlesbyAuthor->links('pagination::bootstrap-4') }}
    </div>

@endsection
