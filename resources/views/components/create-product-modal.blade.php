<div class="modal modal-lg fade" id="createProductModal" wire:ignore.self tabindex="-1" aria-labelledby="createProductModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Add Product</h1>
                <button type="button" class="btn-close" id="cProductModalClose" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" id="addProduct">
                            @csrf
                            <div class="row mb-3">
                                <label for="product_name" class="col-md-4 col-form-label text-md-end">Product Name<span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input id="product_name" type="text" class="form-control" name="product_name" value="{{ old('product_name') }}" required autocomplete="product_name" autofocus>
                                </div>
                            </div>
                            <input type="hidden" name="token" id="token" value="{{ auth()?->user()?->createToken('api')->plainTextToken }}">
                            <div class="row mb-3">
                                <label for="price" class="col-md-4 col-form-label text-md-end">Price<span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input id="price" type="text" class="form-control" name="price" value="{{ old('price') }}" required autocomplete="price" autofocus>
                                </div>
                            </div>
                
                            <div class="row mb-3">
                                <label for="unit" class="col-md-4 col-form-label text-md-end">{{ __('Unit of measure') }}(SET,KG etc..)</label>
                                <div class="col-md-8">
                                    <input id="unit" type="unit" class="form-control" name="unit" value="{{ old('unit') }}" required autocomplete="unit">
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <label for="tax" class="col-md-4 col-form-label text-md-end">Tax%</label>
                                <div class="col-md-8">
                                    <input id="tax" type="text" class="form-control" name="tax" value="{{ old('tax') }}" required autocomplete="tax" autofocus>
                                </div>
                            </div>
                
                            <div class="row mb-3">
                                <label for="description" class="col-md-4 col-form-label text-md-end">Description</label>
                                <div class="col-md-8">
                                    <textarea id="description" type="text" class="form-control" name="description" value="{{ old('description') }}" required autocomplete="description" autofocus></textarea>
                                </div>
                            </div>
                
                            <div class="row mb-3">
                                <label for="hsn" class="col-md-4 col-form-label text-md-end">hsn</label>
                                <div class="col-md-8">
                                    <input id="hsn" type="text" class="form-control" name="hsn" value="{{ old('hsn') }}" required autocomplete="hsn" autofocus>
                                </div>
                            </div>
                
                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script type="module">
        $(document).ready(function() {
            $(document).on('submit', '#addProduct', function(e) {
                e.preventDefault();
                let myform = document.getElementById("addProduct");
                let fd = new FormData(myform);
                $.ajax({
                    url: "{{ route('api.product.store') }}",
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
                        }).then(function() {
                            document.querySelector('[data-bs-target="#addProductModal"]').click();
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
            });

            $(document).on('hidden.bs.modal', '#createProductModal', function() {
                document.querySelector('[data-bs-target="#addProductModal"]').click();
            })
        })
    </script>
@endpush