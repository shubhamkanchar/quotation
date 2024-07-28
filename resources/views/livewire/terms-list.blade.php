<div>
    <div class="modal fade" id="termsModal" wire:ignore.self tabindex="-1" aria-labelledby="addCustomerModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Terms & Conditions</h1>
                    <button type="button" id="termModalClose" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="overflow-y: auto; max-height: 50vh;">
                    <div class="d-flex m-2 justify-content-between">
                        <span class="fw-bold">Select All </span>
                        <input type="checkbox" class="align-self-center me-2" wire:model.live="is_all_selected" style="width: 17px; height: 17px;">
                    </div>
                    @foreach($terms as $term)
                        <div class="card shadow rounded bg-white mb-2">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <span  class="align-self-center"> {{ $term->terms}}</span>
                                    <input type="checkbox" class="align-self-center me-2" wire:model.live="selectedTerms.{{$term->id}}" style="width: 17px; height: 17px;">
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                    <button class="btn btn-dark" type="button" wire:click="saveTerms">Done</button>
                </div>
            </div>
        </div>
    </div>
    
    
        
</div>
@script
    <script>
        $(document).on('hidden.bs.modal', '#addCustomerModal', function() {
            
        })
    </script>
@endscript