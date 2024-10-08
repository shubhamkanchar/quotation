@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header h3">Update Product</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('api.product.store') }}" id="updateProduct">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="token" id="token" value="{{ auth()?->user()?->createToken('api')->plainTextToken }}">
                        <div class="row mb-3">
                            <label for="product_name" class="col-md-4 col-form-label text-md-end">Product Name<span class="text-danger">*</span></label>
                            <div class="col-md-6">
                                <input id="product_name" type="text" class="form-control" name="product_name" value="{{ old('product_name') ?? $ProductModel->product_name  }}" required autocomplete="product_name" autofocus >
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="price" class="col-md-4 col-form-label text-md-end">Price<span class="text-danger">*</span></label>
                            <div class="col-md-6">
                                <input id="price" type="text" class="form-control" name="price" value="{{ old('price') ?? $ProductModel->price }}" required autocomplete="price" autofocus>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="unit" class="col-md-4 col-form-label text-md-end">{{ __('Unit of measure')(SET,KG etc..) }}</label>
                            <div class="col-md-6">
                                <input id="unit" type="unit" class="form-control" name="unit" value="{{ old('unit')?? $ProductModel->unit }}" required autocomplete="unit">
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label for="tax" class="col-md-4 col-form-label text-md-end">Tax%</label>
                            <div class="col-md-6">
                                <input id="tax" type="text" class="form-control" name="tax" value="{{ old('tax')?? $ProductModel->tax }}" required autocomplete="tax" autofocus>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="description" class="col-md-4 col-form-label text-md-end">Description</label>
                            <div class="col-md-6">
                                <textarea id="description" type="text" class="form-control" name="description" value="{{ old('description') ?? $ProductModel->description }}" required autocomplete="description" autofocus></textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="hsn" class="col-md-4 col-form-label text-md-end">hsn</label>
                            <div class="col-md-6">
                                <input id="hsn" type="text" class="form-control" name="hsn" value="{{ old('hsn') ?? $ProductModel->hsn }}" required autocomplete="hsn" autofocus>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Update
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $(document).on('submit', '#updateProduct', function(e) {
                e.preventDefault();
                let myform = document.getElementById("updateProduct");
                let fd = new FormData(myform);
                $.ajax({
                    url: "{{ route('api.product.update',$ProductModel->id) }}",
                    data: fd,
                    cache: false,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    headers: {
                        'Authorization':'Bearer '+$('#token').val()
                    },
                    success: function(res) {
                        Swal.fire({
                            title: "Success",
                            text: res.message,
                            icon: "success"
                        });
                    },
                    error:function(error){
                        console.log(error);
                        Swal.fire({
                            title: "Error",
                            text: error.responseJSON.message,
                            icon: "error"
                        });
                    }
                })
            })
        })
    </script>
@endsection