@extends('layouts.app')

@section('content')
<div class="container">
    <livewire:edit-delivery-notes :deliveryNote="$makeDeliveryNote->id" />
    <livewire:customer-list />
    <livewire:product-list :componentName="'Delivery Notes'"/>
    <livewire:terms-list :id="$makeDeliveryNote->id" :termName="'invoice'" :componentName="'Delivery Notes'"/> />
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
