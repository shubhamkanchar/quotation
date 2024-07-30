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
        })

    </script>
@endsection