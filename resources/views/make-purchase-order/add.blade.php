@extends('layouts.app')

@section('content')
<div class="container">
    <livewire:make-purchase-order />
    <livewire:customer-list />
    <livewire:product-list :componentName="'Purchase Order'"/>
    <livewire:other-charges />
    <livewire:terms-list />
</div>
@endsection
@section('script')
    <script type="module">
        $(document).ready(function() {
        })

    </script>
@endsection