@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header h3">Update Customer</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('api.business.store') }}" id="updateBusiness">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="token" id="token" value="{{ auth()?->user()?->createToken('api')->plainTextToken }}">
                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">Business Name</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') ?? $CustomerModel->name  }}" required autocomplete="name" autofocus >
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">Company Name</label>
                            <div class="col-md-6">
                                <input id="company_name" type="text" class="form-control" name="company_name" value="{{ old('company_name') ?? $CustomerModel->company_name  }}" required autocomplete="company_name" autofocus>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') ?? $CustomerModel->email  }}" required autocomplete="email">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="phone" class="col-md-4 col-form-label text-md-end">{{ __('Phone Number') }}</label>
                            <div class="col-md-6">
                                <input id="phone" type="number" class="form-control " name="number"  value="{{ old('number') ?? $CustomerModel->number  }}" required autocomplete="phone">
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label for="address1" class="col-md-4 col-form-label text-md-end">Address 1</label>
                            <div class="col-md-6">
                                <input id="address1" type="text" class="form-control" name="address_1" value="{{ old('address_1') ?? $CustomerModel->address_1  }}" required autocomplete="address1" autofocus>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="address2" class="col-md-4 col-form-label text-md-end">Address 2</label>
                            <div class="col-md-6">
                                <input id="address2" type="text" class="form-control" name="address_2" value="{{ old('address_2') ?? $CustomerModel->address_2  }}" required autocomplete="address2" autofocus>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="other_info" class="col-md-4 col-form-label text-md-end">Other Info</label>
                            <div class="col-md-6">
                                <input id="other_info" type="text" class="form-control" name="other_info" value="{{ old('other_info') ?? $CustomerModel->other_info  }}" required autocomplete="other_info" autofocus>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="gstin" class="col-md-4 col-form-label text-md-end">GSTIN Number</label>
                            <div class="col-md-6">
                                <input id="gstin" type="text" class="form-control" name="gstin" value="{{ old('gstin') ?? $CustomerModel->gstin  }}" required autocomplete="gstin" autofocus>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="state" class="col-md-4 col-form-label text-md-end">State</label>
                            <div class="col-md-6">
                                <input id="state" type="text" class="form-control" name="state" value="{{ old('state') ?? $CustomerModel->state  }}" required autocomplete="state" autofocus>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="shipping_address" class="col-md-4 col-form-label text-md-end">Shipping Address</label>
                            <div class="col-md-6">
                                <textarea id="shipping_address" type="text" class="form-control" name="shipping_address" required autocomplete="shipping_address" autofocus>{{ old('shipping_address') ?? $CustomerModel->shipping_address  }}</textarea>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Update
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $(document).on('submit', '#updateBusiness', function(e) {
                e.preventDefault();
                let myform = document.getElementById("updateBusiness");
                let fd = new FormData(myform);
                $.ajax({
                    url: "{{ route('api.customer.update',$CustomerModel->id) }}",
                    data: fd,
                    cache: false,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    headers: {
                        'Authorization':'Bearer '+$('#token').val()
                    },
                    success: function(res) {
                        Swal.fire({
                            title: "Success",
                            text: res.message,
                            icon: "success"
                        });
                    },
                    error:function(error){
                        console.log(error);
                        Swal.fire({
                            title: "Error",
                            text: error.responseJSON.message,
                            icon: "error"
                        });
                    }
                })
            })
        })
    </script>
@endsection