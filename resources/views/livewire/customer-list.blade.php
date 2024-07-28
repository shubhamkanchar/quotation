<div wire:ignore.self>
    <div class="modal fade" id="addCustomerModal" wire:ignore.self tabindex="-1" aria-labelledby="addCustomerModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Select Customers</h1>
                    <button type="button" class="btn-close" id="customerModalClose" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="overflow-y: auto; max-height: 50vh;">
                    <div class="row">
                        <div class="col-12">
                            <input type="text" id="customerSearch" wire:model.live.debounce.50ms="customerSearch" class="form-control mb-3" placeholder="Search by Name or Company Name...">
                        </div>
                    </div>
                    @foreach($customers as $customer)
                        <div class="card shadow rounded bg-white mb-2" wire:click="selectCustomer({{$customer->id}})">
                            <div class="card-body">
                            <span class="mb-2"> <b>{{$customer->name}}</b> </span> 
                            <br>
                            {{ $customer->company_name}}
                            <br>
                            {{$customer->number}}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@script
    <script>
        $(document).on('hidden.bs.modal', '#addCustomerModal', function() {
            @this.call('resetSearch');
        })
    </script>
@endscript