<div class="modal fade" wire:ignore.self id="addProductModal" tabindex="-1" aria-labelledby="addCustomerModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Add Quotation Product</h1>
                <button type="button" id="productModalClose" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="overflow-y: auto; max-height: 50vh;" >
                @if (!$selectedProduct)
                    <div class="row">
                        <div class="col-12">
                            <input type="text" id="productSearch" wire:model.live.debounce.50ms="productSearch" class="form-control mb-3" placeholder="Search by Name">
                        </div>
                    </div>
                    @foreach($products as $product)
                        <div class="card shadow rounded bg-white mb-2" wire:click="selectProduct({{$product->id}})">
                            <div class="card-body">
                                <span class="mb-2"> <b>{{$product->product_name}}</b> </span> 
                                <br>
                                {{ $product->description}}
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold">Price</span>
                                    <span class="fw-bold">{{ $product->price}}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold">GST</span>
                                    <span class="fw-bold">{{ $product->gst}}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="row">
                        <form class="form-floating">
                            <div class="col-12">
                                <div class="d-flex justify-content-between mb-2">
                                    <label for="floatingInputValue">Product</label>
                                    <span class="btn btn-sm btn-dark" wire:click="resetProductList">change product</button>
                                </div>
                                <input type="text" disabled value="{{$selectedProduct->product_name}}" class="form-control">
                            </div>
                            <div class="col-12">
                                <label for="floatingInputValue">Quantity</label>
                                <input type="number" value="1" wire:model="quantity" class="form-control @error('quantity') is-invalid @enderror">
                                @error('quantity')
                                    <span class="invalid-feedback">{{ $message}}</span>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="floatingInputValue">Price</label>
                                <input type="number" value="1" wire:model="price" class="form-control @error('price') is-invalid @enderror">
                                @error('price')
                                    <span class="invalid-feedback">{{ $message}}</span>
                                @enderror
                            </div>
                            <div class="col-12 mb-2">
                                <label for="floatingInputValue">Description</label>
                                <textarea name="" class="form-control" wire:model="description">{{$selectedProduct->description}}</textarea>
                                
                            </div>

                            <div class="col-12">
                                <button class="btn btn-dark" type="button" wire:click="addToQuotation">Add To {{$this->componentName}}</button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@script
    <script>
        $(document).on('hidden.bs.modal', '#addProductModal', function() {
            @this.call('resetProductList');
        })
    </script>
@endscript