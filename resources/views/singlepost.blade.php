@extends('layouts.frontend')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <!-- Post Terbaru -->
                <div class="post">
                    <title>{{ $post->tittle }} - Portal Berita</title>
                    <h2>{{ $post->tittle }}</h2>
                    <div class="additional-info">
                        <p class="author-singlepost"><a href="{{ route('author.show', ['author' => $post->author->name]) }}">{{ $post->author->name }}</a></p>
                        <p class="timestamp-singlepost">{{ date('d F, Y', strtotime($post->created_at)) }}</p>
                        <p class="category-singlepost"><a href="{{ route('category.show', ['category' => $post->category_name]) }}">{{ $post->category_name }}</a></p>
                    </div>
                    <img src="{{ asset('storage/' . $post->image) }}" alt="Image" class="img-fluid mb-3">
                    <p style="text-align: justify;">{{ strip_tags($post->content) }}</p>
                </div>
            </div>
            <div class="col-md-3 mt-5">
                <!-- Tampilan Latest (Artikel Terbaru) -->
                <div class="latest">
                    <h6 style="font-weight: bold;">Latest</h6>
                    @foreach ($latestArticles as $latest)
                        <div class="latest-item-custom">
                            <div style="display: flex; align-items: center;">
                                <img class="mt-2" src="{{ asset('storage/' . $latest->image) }}" alt="Image"
                                    style="max-width: 100px; margin-right: 10px;">
                                <h6>
                                    <a href="{{ route('singlepost.index', ['slug' => $latest->slug]) }}">
                                        {{ $latest->tittle }}
                                    </a>
                                </h6>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div>
                <i class="fas fa-tag"></i>
                <span>Tagged:</span>
                @foreach ($tags as $index => $tag)
                    @if (in_array($tag->name, explode(',', $post->tag_name)))
                        <a href="{{ route('tagpost', ['tag' => $tag->name]) }}" class="tag-singlepost">
                            {{ $tag->name }}
                        </a>
                        @if (!$loop->last)
                            <!-- Jika ini bukan tag terakhir, tambahkan spasi -->
                            &nbsp;
                        @endif
                    @endif
                @endforeach
            </div>
        </div>
    </div>
@endsection
