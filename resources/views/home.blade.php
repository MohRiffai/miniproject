@extends('layouts.frontend')
@section('content')
    <!DOCTYPE html>
    <html>

    <head>
        <title>Portal Berita</title>
        <link rel="stylesheet" type="text/css"
            href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
        <link rel="stylesheet" type="text/css"
            href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />
    </head>

    <body>
        <div class="slider">
            @foreach ($latestArticlesSlider as $item)
                <div class="slide">
                    <a href="{{ route('singlepost.index', ['slug' => $item->slug]) }}">
                        <img src="{{ asset('storage/' . $item->image) }}" alt="Image">
                    </a>
                    <div class="caption">
                        <h2><a href="{{ route('singlepost.index', ['slug' => $item->slug]) }}">{{ $item->tittle }}</a></h2>
                        <!-- Tambahan informasi berita lainnya -->
                        <div class="additional-info">
                            <p class="author"><a href="{{ route('author.show', ['author' => $item->author->name]) }}">{{ $item->author->name }}</a></p>
                            <p class="timestamp">{{ date('d M, Y', strtotime($item->created_at)) }}</p>
                        </div>
                        <p class="category"><a href="{{ route('category.show', ['category' => $item->category_name]) }}">{{ $item->category_name }}</a></p>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="all-news">
            <h4>All News</h4>
        </div>
        <div class="article-container">
            @foreach ($latestArticles as $item)
                <div class="article">
                    <a href="{{ route('singlepost.index', ['slug' => $item->slug]) }}">
                        <img src="{{ asset('storage/' . $item->image) }}" alt="Image">
                    </a>
                    <h2><a href="{{ route('singlepost.index', ['slug' => $item->slug]) }}">{{ $item->tittle }}</a></h2>
                    <div class="additional-info">
                        <p class="author"><a href="{{ route('author.show', ['author' => $item->author->name]) }}">{{ $item->author->name }}</a></p>
                        <p class="timestamp">{{ date('d M, Y', strtotime($item->created_at)) }}</p>
                    </div>
                    <p>{{ substr(strip_tags($item->content), 0, 150) }}...</p>
                    <p class="category"><a href="{{ route('category.show', ['category' => $item->category_name]) }}">{{ $item->category_name }}</a></p>
                </div>
            @endforeach
        </div>            
        <div class="pagination-links mt-4 d-flex justify-content-center">
            {{ $latestArticles->links('pagination::bootstrap-4') }}
        </div>
        <!-- Tautan CDN untuk jQuery (dibutuhkan oleh Slick Carousel) -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Tautan CDN untuk Slick Carousel -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>

        <script>
            $(document).ready(function() {
                $('.slider').slick({
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    autoplay: true,
                    autoplaySpeed: 7000, // Atur kecepatan otomatis
                    dots: true // Tampilkan navigasi titik (dots)
                });
            });
        </script>
    </body>

    </html>
@endsection
