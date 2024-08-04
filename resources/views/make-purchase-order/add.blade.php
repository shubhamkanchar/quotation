@extends('layouts.app')

@section('content')
<div class="container">
    <livewire:make-purchase-order />
    <livewire:customer-list />
    <livewire:product-list :componentName="'Purchase Order'"/>
    <livewire:other-charges />
    <livewire:terms-list :termName="'Purchase Order'"/>
</div>
@endsection
@section('script')
    <script type="module">
        $(document).ready(function() {
            $(document).on('purchaseOrderCreated', function($event) {
                let route = $event.detail[0];
                Swal.fire({
                    title: "Success",
                    text: 'Purchase Order Created Successfully',
                    icon: "success"
                }).then((result) => { 
                    window.location.href = route;
                });
            })
        })

    </script>
@endsection