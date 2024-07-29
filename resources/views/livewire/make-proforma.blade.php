<div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card bg-white p-2">
                <div class="card-header rounded-0 border-bottom-0 bg-secondary-subtle">
                    <div class="d-flex justify-content-between">
                        <div class="text-secondary ms-2">
                            <span >Proforma Invoice Date</span>
                            <br>
                            <input type="date" class="form-control" id="proformaDate" wire:model="proforma_date">   
                        </div>
                        <div class="text-secondary ms-2">
                            <span >Proforma Invoice No</span>
                            <br>
                            -
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form>
                        @csrf
                        <div class="row mb-2">
                            <div class="card-header bg-secondary-subtle rounded">
                                <div class="d-flex justify-content-between">
                                    <span class="align-self-center"> <b>BILL TO</b></span>
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

                            @if ($addedProducts)  
                                @foreach ($addedProducts as $index => $product)     
                                    <div class="card mt-2 shadow rounded bg-white mb-2">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <span class="fw-bold">{{ $product['product']['product_name'] }}</span> 
                                                <span> <i class="fas fa-trash text-dark" wire:click="removeProduct({{$index}})" role="button"></i></span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="text-secondary">Amount</span> 
                                                <span> {{$product['quantity']}} * &#8377;{{ $product['price']}} = &#8377;{{ (int)$product['quantity'] * (int)$product['price'] }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="text-secondary">Total amount</span> 
                                                <span class="fw-bold"> &#8377;{{ (int)$product['quantity'] * (int)$product['price'] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach  
                            @endif
                        </div>

                        <div class="row mb-2">
                            <div class="card-header mb-2 bg-secondary-subtle rounded">
                                <div class="d-flex justify-content-between">
                                    <span class="align-self-center"> <b>OTHER CHARGE</b></span>
                                    <button class="btn btn-dark" type="button" data-bs-toggle="modal" data-bs-target="#otherChargeModal">+</button>
                                </div>
                            </div>
                            @if($otherCharges)
                                <div class="card shadow rounded bg-white">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <span  class="align-self-center"> {{ $otherCharges['other_charge_label']}}</span>
                                            <span> &#8377;{{ $otherCharges['other_charge_amount']}} <i class="fas fa-trash text-dark" wire:click="removeCharges()" role="button"></i></span>
                                        </div>
                                        @if ($otherCharges['is_taxable'])    
                                            <div class="d-flex justify-content-between">
                                                <span  class="align-self-center text-secondary">  GST ({{$otherCharges['gst_percentage']}}%);</span>
                                                <span class="me-3"> &#8377; {{ $otherCharges['gst_amount'] }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            
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

                        <div class="row mb-4">
                            <div class="card-header bg-secondary-subtle rounded">
                                <div class="d-flex justify-content-between">
                                    <span class="align-self-center"> <b>ROUND OFF AMOUNT</b></span>
                                    <input type="checkbox" id="roundOff" wire:model="round_off" class="align-self-center me-2" style="width: 17px; height: 17px;">
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="card-header mb-2 bg-secondary-subtle rounded">
                                <div class="d-flex justify-content-between">
                                    <span class="align-self-center"> <b>Paid Info</b></span>
                                    <button class="btn btn-dark" type="button" data-bs-toggle="modal" data-bs-target="#addPaidInfoModal">+</button>
                                </div>
                            </div>
                            @if($paidAmount)    
                                <div class="card shadow rounded bg-white mb-2">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <span  class="align-self-center">Paid Amount</span>
                                            <span>{{$paidAmount}}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
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
                                <div class="d-flex justify-content-between">
                                    <div class="text-white ms-2">
                                        <span >Amount Due</span>
                                        <br>
                                        {{-- @if ($totalAmount) --}}
                                        &#8377;{{ $totalAmount }}
                                        {{-- @endif --}}
                                    </div>
                                    <button class="rounded-pill btn bg-white py-2 px-4" type="button" wire:click="generatePdf">Generate</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@script
    <script type="module">
        $(document).on('change', '#roundOff',function() {
            @this.call('roundOff', $(this).is(':checked'))
        })
        $(document).on('customerAdded', function(event) {
            document.getElementById('customerModalClose').click()
        })

        $(document).on('productAdded', function(event) {
            document.getElementById('productModalClose').click();
        })

        $(document).on('termsAdded', function(event) {
            document.getElementById('termModalClose').click();
        })

        $(document).on('otherChargesAdded', function(event) {
            document.getElementById('chargeModalClose').click();
        })
    </script>
@endscript