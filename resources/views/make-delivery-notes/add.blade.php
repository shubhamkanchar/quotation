@extends('layouts.app')

@section('content')
<div class="container">
    <livewire:make-delivery-notes />
    <livewire:customer-list />
    <livewire:product-list :componentName="'Delivery Notes'"/>
    <livewire:terms-list :termName="'invoice'" />
    <x-create-customer-modal />
    <x-create-product-modal />
</div>
@endsection
@section('script')
    <script type="module">
        $(document).ready(function() {
            $(document).on('deliveryNoteCreated', function($event) {
                let route = $event.detail[0];
                Swal.fire({
                    title: "Success",
                    text: 'Delivery Note Created Successfully',
                    icon: "success"
                }).then((result) => { 
                    window.location.href = route;
                });
            })
        })

    </script>
@endsection
