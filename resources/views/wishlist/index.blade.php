@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Wishlist Saya</h2>

    @if($wishlists->count() > 0)
        <div class="row">
            @foreach($wishlists as $item)
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <img src="{{ $item->product->primaryImage->url ?? 'https://via.placeholder.com/150' }}" class="card-img-top">
                        
                        <div class="card-body">
                            <h5>{{ $item->product->name }}</h5>
                            <p>Rp {{ number_format($item->product->price) }}</p>

                            <form action="{{ url('/wishlist/'.$item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{ $wishlists->links() }}
    @else
        <p>Wishlist masih kosong.</p>
    @endif
</div>
@endsection