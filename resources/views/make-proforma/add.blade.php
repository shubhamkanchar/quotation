@extends('layouts.app')

@section('content')
<div class="container">
    <livewire:make-proforma />
    <livewire:customer-list />
    <livewire:product-list :componentName="'Proforma Invoice'"/>
    <livewire:other-charges />
    <livewire:paid-info />
    <livewire:terms-list :termName="'invoice'"/>
</div>
@endsection
@section('script')
    <script type="module">
        
    </script>
@endsection