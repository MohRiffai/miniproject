@extends('layouts.frontend')

<body>
    <h1>Selamat datang di Portal Berita</h1>

    <div>
        @foreach($home as $item)
            <div>
                <h2>{{ $item->tittle }}</h2>
                <p>{{ $item->getExcerpt() }}</p>
                <!-- Tambahan informasi berita lainnya -->
            </div>
            <hr>
        @endforeach

        <!-- Tampilkan pagination links jika menggunakan paginate() -->
        {{ $home->links() }}
    </div>
</body>
