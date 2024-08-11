@extends('layouts.app')

@section('content')
<div class="container">
    <livewire:make-proforma />
    <livewire:customer-list />
    <livewire:product-list :componentName="'Proforma Invoice'"/>
    <livewire:other-charges />
    <livewire:paid-info />
    <livewire:terms-list :termName="'invoice'"/>
    <x-create-customer-modal />

</div>
@endsection
@section('script')
    <script type="module">
        $(document).on('ProformaInvoiceCreated', function($event) {
            let route = $event.detail[0];
            Swal.fire({
                title: "Success",
                text: 'Proforma Created Successfully',
                icon: "success"
            }).then((result) => { 
                window.location.href = route;
            });
        })
    </script>
@endsection