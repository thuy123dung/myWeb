@extends('layouts.front')

@section('title')
    Welcome to E-shop
@endsection

@section('content')
    @include('layouts.inc.slider')

    <div class="py-5">
        <div class="container">
            <div class="row">
                <h2>Feature Products</h2>
                @foreach ($feature_product as $prod)
                    <div class="col-md-3 mb-3">
                        <div class="card">
                            <img src="{{ asset('assets/uploads/product/' . $prod->image) }}" alt="Product Image">
                            <div class="card-body">
                                <h5>{{ $prod->name }}</h5>
                                <span class="float-start">{{ $prod->selling_price }}</span>
                                <span class="float-end"><s>{{ $prod->original_price }}</s></span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="py-5">
        <div class="container">
            <div class="row">
                <h2>Trending Category</h2>
                @foreach ($trending_category as $tcategory)
                    <div class="col-md-3 mb-3">
                        <a href="{{ url('view-category/'. $tcategory->slug) }}">
                            <div class="card">
                                <img src="{{ asset('assets/uploads/category/' . $tcategory->image) }}" alt="Product Image">
                                <div class="card-body">
                                    <h5>{{ $tcategory->name }}</h5>
                                    <p>
                                        {{ $tcategory->description }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
