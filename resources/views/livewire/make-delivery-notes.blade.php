<div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if($user->business)
                <div class="card bg-white p-2">
                    <div class="card-header rounded-0 border-bottom-0 bg-secondary-subtle">
                        <div class="d-flex justify-content-between">
                            <div class="text-secondary ms-2">
                                <div class="d-flex">
                                    <div>
                                        <span >Delivery Date</span>
                                        <br>
                                        <input type="date" class="form-control" id="deliveryDate" wire:model="delivery_date">
                                    </div>
                                    <div class="ms-2">
                                        <span>Ref No</span>
                                        <br>
                                        <input type="text" class="form-control" id="referenceNo" wire:model="reference_no">
                                    </div>
                                </div>
                            </div>
                            <div class="text-secondary ms-2">
                                <span >Delivery Note No</span>
                                <br>
                                -
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('api.term.store') }}" id="addQuotation">
                            @csrf
                            <div class="row mb-2">
                                <div class="card-header bg-secondary-subtle rounded">
                                    <div class="d-flex justify-content-between">
                                        <span class="align-self-center"> <b>TO</b></span>
                                        <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#addCustomerModal">+</button>
                                    </div>
                                </div>
                                @if ($addedCustomer)    
                                    <div id="selectedCustomer" class="card mt-2 shadow rounded bg-white mb-2">
                                        <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-bold" id="customerName">{{$addedCustomer->name}}</span> 
                                            <span> <i class="fas fa-trash text-dark" wire:click="removeCustomer" role="button"></i></span>
                                        </div>
                                        <span id="customerCompany">{{$addedCustomer->company_name}}</span>
                                        <br>
                                        <span id="customerNumber">{{$addedCustomer->number}}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="row mb-2">
                                <div class="card-header bg-secondary-subtle rounded">
                                    <div class="d-flex justify-content-between">
                                        <span class="align-self-center"> <b>PRODUCTS</b></span>
                                        <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#addProductModal" type="button">+</button>
                                    </div>
                                </div>
                                <div wire:sortable="updateProductOrder" wire:sortable.options="{ animation: 100 }">
                                    @if ($addedProducts)  
                                        @foreach ($addedProducts as $index => $product)     
                                            <div class="card mt-2 shadow rounded bg-white mb-2"  wire:sortable.item="{{ $index }}" wire:index="task-{{ $index }}">
                                                @if (count($addedProducts) > 1)
                                                    <div class="card-header bg-white">
                                                        <i class="fa-solid fa-up-down-left-right" wire:sortable.handle style="cursor: grab"></i>
                                                    </div>
                                                @endif
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between">
                                                        <div>
                                                            <span class="fw-bold">{{ $product['product']['product_name'] }}</span> 
                                                            <br>
                                                            <span class="text-secondary">Quantity</span>
                                                        </div>
                                                        <div>
                                                            <span> <i class="fas fa-trash text-dark" wire:click="removeProduct({{$index}})" role="button"></i></span>
                                                            <br>
                                                            <span class="text-secondary">{{number_format((float)$product['quantity'], 1)}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach  
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="card-header mb-2 bg-secondary-subtle rounded">
                                    <div class="d-flex justify-content-between">
                                        <span class="align-self-center"> <b>TERMS & CONDITIONS</b></span>
                                        <button class="btn btn-dark" type="button" data-bs-toggle="modal" data-bs-target="#termsModal">+</button>
                                    </div>
                                </div>
                                @foreach($addedTerms as $term)
                                    <div class="card shadow rounded bg-white mb-2">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <span  class="align-self-center"> {{ $term->terms}}</span>
                                                <span> <i class="fas fa-trash text-dark" wire:click="removeTerms({{$term->id}})" role="button"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if ($errors->any())
                                <div class="alert alert-dark alert-dismissible fade show" role="alert">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            <div class="row mb-0">
                                <div class="card-header bg-dark rounded-pill">
                                    <div class="d-flex justify-content-end">
                                        <button class="rounded-pill btn bg-white py-2 px-4" type="button" wire:click="generatePdf">Generate</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <div class="card bg-white p-2">
                    <div class="card-header rounded-0 border-bottom-0 bg-secondary-subtle">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="card-title align-self-center">
                                <h4 class="mt-2">Please add your business first </h4>
                            </div>
                            <a href="{{route('business.create')}}" class="btn btn-dark">Add business</a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@script
    <script type="module">
    
        $(document).on('customerAdded', function(event) {
            document.getElementById('customerModalClose').click()
        })

        $(document).on('productAdded', function(event) {
            document.getElementById('productModalClose').click();
        })

        $(document).on('termsAdded', function(event) {
            document.getElementById('termModalClose').click();
        })

    </script>
@endscript