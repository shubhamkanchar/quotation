@extends('layouts.app')

@section('content')
<div class="container">
    <livewire:edit-delivery-notes :deliveryNote="$makeDeliveryNote->uuid" />
    <livewire:customer-list />
    <livewire:product-list :componentName="'Delivery Notes'"/>
    <livewire:terms-list :id="$makeDeliveryNote->id" :termName="'invoice'" :componentName="'Delivery Notes'"/>
    <x-create-customer-modal />
    <x-create-product-modal />
</div>
@endsection
@section('script')
    <script type="module">
        $(document).ready(function() {
            $(document).on('deliveryNoteUpdated', function($event) {
                Swal.fire({
                    title: "Success",
                    text: 'Delivery Note Updated Successfully',
                    icon: "success"
                })
            })
        })

    </script>
@endsection
