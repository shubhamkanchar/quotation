@extends('layouts.app')

@section('content')
<div class="container">
    <livewire:make-delivery-notes />
    <livewire:customer-list />
    <livewire:product-list :componentName="'Delivery Notes'"/>
    <livewire:terms-list :termName="'invoice'" />
</div>
@endsection
@section('script')
    <script type="module">
        $(document).ready(function() {
        })

    </script>
@endsection