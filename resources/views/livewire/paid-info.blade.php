<div class="modal fade" wire:ignore.self id="addPaidInfoModal" tabindex="-1" aria-labelledby="addCustomerModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Paid Infos</h1>
                <button type="button" id="productModalClose" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="overflow-y: auto; max-height: 50vh;">
                @if ($showInfo)    
                    <div class="row">
                        <form class="form-floating">
                            <div class="col-12">
                                <label for="floatingInputValue">Paid Date</label>
                                <input type="date" wire:model="paid_date" class="form-control @error('paid_date') is-invalid @enderror">
                                @error('paid_date')
                                    <span class="invalid-feedback">{{ $message}}</span>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="floatingInputValue">Amount</label>
                                <input type="number" value="1" wire:model="paid_amount" class="form-control @error('paid_amount') is-invalid @enderror">
                                @error('paid_amount')
                                    <span class="invalid-feedback">{{ $message}}</span>
                                @enderror
                            </div>
                            <div class="col-12 mb-2">
                                <label for="floatingInputValue">Notes</label>
                                <textarea name="" class="form-control" wire:model="notes"></textarea>
                                
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <button class="btn btn-dark" type="button" wire:click="addInfo">Save</button>
                                    <button wire:click="resetForm" type="button" class="btn btn-dark mt-2">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                @else
                    @foreach ($paidInfos as $index => $info)
                        <div class="card mb-2 bg-white shadow">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <span><b>Amount</b></span>
                                    <div>
                                        <span> &#8377;{{$info['amount']}}</span>
                                        <span><i class="me-2 fas fa-trash text-dark" wire:click="removeInfo({{$index}})"></i></span>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Paid Date</span>
                                    <span>{{$info['date']}}</span>
                                </div>
                                <div class="d-flex">
                                    <span>{{$info['notes']}}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <button wire:click="showForm" class="btn btn-dark mt-2">Add Paid Amount</button>
                @endif
            </div>
        </div>
    </div>
</div>
