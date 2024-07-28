<div>
    <div class="modal fade" wire:ignore.self id="otherChargeModal" tabindex="-1" aria-labelledby="addCustomerModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Other Charges Info</h1>
                    <button type="button" id="chargeModalClose" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-2">
                            <label for="floatingInputValue">Other Charge Label</label>
                            <input type="text" wire:model="other_charge_label" class="form-control @error('other_charge_label') is-invalid @enderror">
                            @error('other_charge_label')
                                <span class="invalid-feedback">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="col-12 mb-2">
                            <label for="floatingInputValue">Other charge Amount</label>
                            <input type="number" wire:model="other_charge_amount" class="form-control @error('other_charge_amount') is-invalid @enderror">
                            @error('other_charge_label')
                                <span class="invalid-feedback">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="col-12 mb-2">
                            <div class="d-flex justify-content-between">
                                <span class="text-secondary">Is taxable?</span>
                                <input type="checkbox" wire:model.live="is_taxable">
                            </div>
                        </div>
                        @if ($is_taxable)
                            <div class="col-12 mb-2">
                                <label for="floatingInputValue">Gst(IN%)</label>
                                <input type="number" wire:model="gst_percentage" class="form-control @error('gst_percentage') is-invalid @enderror">
                                @error('gst_percentage')
                                    <span class="invalid-feedback">{{$message}}</span>
                                @enderror
                            </div>
                        @endif
                
                        <div class="col-12">
                            <button class="btn btn-dark" type="button" wire:click="saveCharges">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
