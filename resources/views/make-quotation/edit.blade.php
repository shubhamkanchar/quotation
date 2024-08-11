@extends('layouts.app')
@section('content')
<div class="container">
    <livewire:edit-quotation :quotation="$makeQuotation->uuid"/>
    <livewire:customer-list />
    <livewire:product-list :componentName="'Invoice'"/>
    @if ($makeQuotation->otherCharge)   
        <livewire:other-charges :otherCharge="$makeQuotation->otherCharge->id"/>
    @else
        <livewire:other-charges/>
    @endif
    <livewire:terms-list :termName="'Quotation'" :id="$makeQuotation->id" :componentName="'Invoice'"/>
    <x-create-customer-modal />
    
</div>
@endsection
@section('script')
    <script type="module">
        $(document).ready(function() {
           
        $(document).on('quotationUpdated', function() {
            Swal.fire({
                title: "Success",
                text: 'Quotation Updated Successfully',
                icon: "success"
            });
        });
        })

    </script>
@endsection