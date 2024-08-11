@extends('layouts.app')

@section('content')
<div class="container">
    <livewire:edit-proforma :invoice="$makeProformaInvoice->uuid"/>
    <livewire:customer-list />
    <livewire:product-list :componentName="'Proforma Invoice'"/>
    @if ($makeProformaInvoice->otherCharge)   
        <livewire:other-charges :otherCharge="$makeProformaInvoice->otherCharge->id"/>
    @else
        <livewire:other-charges/>
    @endif
    @if ($makeProformaInvoice->paidInfos)   
        @php $ids = $makeProformaInvoice->paidInfos->pluck('id'); @endphp
        <livewire:paid-info  :paidInfoIds="$ids"/>
    @else
        <livewire:paid-info />
    @endif
    <livewire:terms-list :termName="'Proforma Invoice'" :id="$makeProformaInvoice->id" :componentName="'Proforma Invoice'"/>
    <x-create-customer-modal />
    <x-create-product-modal />
</div>
@endsection
@section('script')
    <script type="module">
        $(document).on('proformaInvoiceUpdated', function($event) {
            Swal.fire({
                title: "Success",
                text: 'Invoice Updated Successfully',
                icon: "success"
            })
        })
    </script>
@endsection