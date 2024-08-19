@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                   <div class="row">
                        <div class="offset-md-2 col-md-4">
                            <a href="{{ route('business.index') }}"><div class="card mt-2">
                                <div class="card-body">Business</div>
                            </div></a>
                            <a href="{{ route('customer.index') }}"><div class="card mt-2">
                                <div class="card-body">Customer</div>
                            </div></a>
                            <a href="{{ route('product.index') }}"><div class="card mt-2">
                                <div class="card-body">Product</div>
                            </div></a>
                            <a href="{{ route('term.index') }}"><div class="card mt-2">
                                <div class="card-body">Terms</div>
                            </div></a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('make-quotation.create') }}"><div class="card mt-2">
                                <div class="card-body">Make Quotation</div>
                            </div></a>
                            <a href="{{ route('make-invoice.create') }}"><div class="card mt-2">
                                <div class="card-body">Make Invoice</div>
                            </div></a>
                            <a href="{{ route('make-purchase-order.create') }}"><div class="card mt-2">
                                <div class="card-body">Make purchase order</div>
                            </div></a>
                            <a href="{{ route('make-proforma-invoice.create') }}"><div class="card mt-2">
                                <div class="card-body">Make proforma invoice</div>
                            </div></a>
                            <a href="{{ route('make-delivery-note.create') }}"><div class="card mt-2">
                                <div class="card-body">Make delivery notes</div>
                            </div></a>
                        </div>
                   </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
