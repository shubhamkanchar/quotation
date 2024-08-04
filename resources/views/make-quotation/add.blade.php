@extends('layouts.app')
@section('content')
<div class="container">
    <livewire:make-quotation />
    <livewire:customer-list />
    <livewire:product-list :componentName="'Quotation'"/>
    <livewire:other-charges />
    <livewire:terms-list :termName="'Quotation'"/>
</div>
@endsection
@section('script')
    <script type="module">
        $(document).ready(function() {
            $(document).on('quotationCreated', function($event) {
                let route = $event.detail[0];
                Swal.fire({
                    title: "Success",
                    text: 'Quotation Created Successfully',
                    icon: "success"
                }).then((result) => { 
                    window.location.href = route;
                });
            })
        })

    </script>
@endsection