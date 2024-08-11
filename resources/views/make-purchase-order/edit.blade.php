@extends('layouts.app')

@section('content')
<div class="container">
    <livewire:edit-purchase-order :purchaseOrder="$makePurchaseOrder->uuid"/>
    <livewire:customer-list />
    <livewire:product-list :componentName="'Purchase Order'"/>
    @if ($makePurchaseOrder->otherCharge)   
        <livewire:other-charges :otherCharge="$makePurchaseOrder->otherCharge->id"/>
    @else
        <livewire:other-charges/>
    @endif
    <livewire:terms-list :termName="'Purchase Order'" :id="$makePurchaseOrder->id" :componentName="'Purchase Order'"/>
    <x-create-customer-modal />
    <x-create-product-modal />
</div>
@endsection
@section('script')
    <script type="module">
        $(document).ready(function() {
            $(document).on('purchaseOrderUpdated', function($event) {
                Swal.fire({
                    title: "Success",
                    text: 'Purchase Order Updated Successfully',
                    icon: "success"
                })
            })
        })

    </script>
@endsection