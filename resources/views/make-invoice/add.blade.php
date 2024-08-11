@extends('layouts.app')

@section('content')
<div class="container">
    <livewire:make-invoice />
    <livewire:customer-list />
    <livewire:product-list :componentName="'Invoice'"/>
    <livewire:other-charges />
    <livewire:paid-info />
    <livewire:terms-list :termName="'invoice'"/>
    <x-create-customer-modal />
    <x-create-product-modal />

</div>
@endsection
@section('script')
    <script type="module">
        $(document).on('invoiceCreated', function($event) {
            let route = $event.detail[0];
            Swal.fire({
                title: "Success",
                text: 'Invoice Created Successfully',
                icon: "success"
            }).then((result) => { 
                window.location.href = route;
            });
        })
    </script>
@endsection