@extends('layouts.front')

@section('title', $product->name)

@section('content')
    <div class="py-3 mb-4 shadow-sm bg-warning border-top">
        <div class="container">
            <h6 class="mb-0">Collections/ {{ $product->category->name }} / {{ $product->name }}</h6>
        </div>
    </div>

    <div class="container">
        <div class="row product_data">
            <div class="col-md-4 border-right">
                <img src="{{ asset('assets/uploads/product/' . $product->image) }}" class="w-100" alt="">
            </div>
            <div class="col-md-8">
                <h2 class="mb-0">
                    {{ $product->name }}
                    @if ($product->trending == '1')
                        <label style="font-size: 16px;" class="float-end badge bg-danger trending_tag">Trending</label>
                    @endif
                </h2>

                <hr>
                <label class="me-3">Original price: <s> $ {{ $product->original_price }}</s></label>
                <label class="fw-bold">Selling price: $ {{ $product->selling_price }}</label>
                <p class="mt-3">
                    {!! $product->small_description !!}
                </p>
                <hr>
                @if ($product->qty > 0)
                    <label class="badge bg-success">In stock</label>
                @else
                    <label class="badge bg-danger">Out of stock</label>
                @endif
                <div class="row mt-2">
                    <div class="col-md-2">
                        <input type="hidden" value="{{ $product->id }}" class="prod_id">
                        <label for="quantity">Quantity</label>
                        <div class="input-group text-center mb-3" style="width: 110px">
                            <button type="button" id="decrement" class="input-group-text">-</button>
                            <input type="text" id="quantity" value="1" class="form-control">
                            <button type="button" id="increment" class="input-group-text">+</button>
                        </div>
                    </div>
                    <div class="col-md-10">
                        <br />
                        <button type="button" class="btn btn-primary me-3 float-start" id="addToCart">Add to Cart
                            <i class="fas fa-shopping-cart"></i>
                        </button>
                        <button type="button" class="btn btn-success me-3 float-start">Add to Wishlist
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $("#increment").click(function() {
                var quantity = parseInt($("#quantity").val());
                $("#quantity").val(quantity + 1);
            });

            $("#decrement").click(function() {
                var quantity = parseInt($("#quantity").val());
                if (quantity > 1) {
                    $("#quantity").val(quantity - 1);
                }
            });

            $('#addToCart').click(function(e) {
                e.preventDefault();

                var product_id = $(this).closest('.product_data').find('.prod_id').val();
                var product_qty = $(this).closest('.product_data').find('#quantity').val();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    method: "POST",
                    url: "/add-to-cart",
                    data: {
                        'product_id': product_id,
                        'product_qty': product_qty,
                    },
                    success: function(response) {
                        swal(response.status);
                    }
                });
            });

        });
    </script>

@endsection
