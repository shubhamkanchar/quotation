@extends('layouts.app')

@section('content')
<div class="container">
    <livewire:make-quotation />
    <livewire:customer-list />
    <livewire:product-list />
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