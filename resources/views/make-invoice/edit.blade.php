@extends('layouts.app')

@section('content')
<div class="container">
    <livewire:edit-invoice :invoice="$makeInvoice->uuid"/>
    <livewire:customer-list />
    <livewire:product-list :componentName="'Invoice'"/>
    @if ($makeInvoice->otherCharge)   
        <livewire:other-charges :otherCharge="$makeInvoice->otherCharge->id"/>
    @else
        <livewire:other-charges/>
    @endif
    @if ($makeInvoice->paidInfos)   
        @php $ids = $makeInvoice->paidInfos->pluck('id'); @endphp
        <livewire:paid-info  :paidInfoIds="$ids"/>
    @else
        <livewire:paid-info />
    @endif
    <livewire:terms-list :termName="'invoice'" :id="$makeInvoice->id" :componentName="'Invoice'"/>
    <x-create-customer-modal />  
</div>
@endsection
@section('script')
    <script type="module">
        $(document).on('invoiceUpdated', function($event) {
            let route = $event.detail[0];
            Swal.fire({
                title: "Success",
                text: 'Invoice Updated Successfully',
                icon: "success"
            })
        })
    </script>
@endsection