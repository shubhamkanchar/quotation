@extends('layouts.app')
@section('content')
<div class="container">
    <livewire:edit-quotation :quotation="$makeQuotation->id"/>
    <livewire:customer-list />
    <livewire:product-list :componentName="'Quotation'"/>
    @if ($makeQuotation->otherCharge)   
        <livewire:other-charges :otherCharge="$makeQuotation->otherCharge->id"/>
    @else
        <livewire:other-charges/>
    @endif
    <livewire:terms-list :termName="'Quotation'" :id="$makeQuotation->id" :componentName="'Quotation'"/>
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