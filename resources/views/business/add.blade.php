@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header h3">Add Bussiness</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('api.business.store') }}" id="addBusiness">
                        @csrf
                        <div class="row mb-3">
                            <label for="logo" class="col-md-4 col-form-label text-md-end">logo</label>
                            <div class="col-md-6">
                                <input id="logo" type="file" class="form-control" name="logo" value="{{ old('logo') }}" required autocomplete="logo" autofocus>
                            </div>
                        </div>
                        <input type="hidden" name="token" id="token" value="{{ auth()?->user()?->createToken('api')->plainTextToken }}">
                        <div class="row mb-3">    
                            <label for="signature" class="col-md-4 col-form-label text-md-end">Signature</label>
                            <div class="col-md-6">
                                <input id="signature" type="file" class="form-control" name="signature" value="{{ old('signature') }}" required autocomplete="signature" autofocus>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="business_name" class="col-md-4 col-form-label text-md-end">Business Name</label>
                            <div class="col-md-6">
                                <input id="business_name" type="text" class="form-control" name="business_name" value="{{ old('business_name') }}" required autocomplete="business_name" autofocus>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">Contact Name</label>
                            <div class="col-md-6">
                                <input id="contact_name" type="text" class="form-control" name="contact_name" value="{{ old('contact_name') }}" required autocomplete="contact_name" autofocus>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autocomplete="email">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="phone" class="col-md-4 col-form-label text-md-end">{{ __('Mobile Number') }}</label>
                            <div class="col-md-6">
                                <input id="phone" type="tel" class="form-control " name="number"  value="{{ old('phone') }}" required autocomplete="phone">
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label for="address1" class="col-md-4 col-form-label text-md-end">Address 1</label>
                            <div class="col-md-6">
                                <input id="address1" type="text" class="form-control" name="address_1" value="{{ old('address1') }}" required autocomplete="address1" autofocus>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="address2" class="col-md-4 col-form-label text-md-end">Address 2</label>
                            <div class="col-md-6">
                                <input id="address2" type="text" class="form-control" name="address_2" value="{{ old('address2') }}" required autocomplete="address2" autofocus>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="address3" class="col-md-4 col-form-label text-md-end">Address 3</label>
                            <div class="col-md-6">
                                <input id="address3" type="text" class="form-control" name="address_3" value="{{ old('address3') }}" required autocomplete="address3" autofocus>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="other_info" class="col-md-4 col-form-label text-md-end">Other Info</label>
                            <div class="col-md-6">
                                <input id="other_info" type="text" class="form-control" name="other_info" value="{{ old('other_info') }}" required autocomplete="other_info" autofocus>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="bussiness_label" class="col-md-4 col-form-label text-md-end">Business Label</label>
                            <div class="col-md-6">
                                <input id="bussiness_label" type="text" class="form-control" name="business_label" value="{{ old('bussiness_label') }}" required autocomplete="bussiness_label" autofocus>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="bussiness_number" class="col-md-4 col-form-label text-md-end">Business Number</label>
                            <div class="col-md-6">
                                <input id="bussiness_number" type="text" class="form-control" name="business_number" value="{{ old('bussiness_number') }}" required autocomplete="bussiness_number" autofocus>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="state" class="col-md-4 col-form-label text-md-end">State</label>
                            <div class="col-md-6">
                                <input id="state" type="text" class="form-control" name="state" value="{{ old('state') }}" required autocomplete="state" autofocus>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="bussiness_category" class="col-md-4 col-form-label text-md-end">Business Category</label>
                            <div class="col-md-6">
                                <input id="bussiness_category" type="text" class="form-control" name="business_category" value="{{ old('bussiness_category') }}" required autocomplete="bussiness_category" autofocus>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="account_name" class="col-md-4 col-form-label text-md-end">Account Number</label>
                            <div class="col-md-6">
                                <input id="account_name" type="text" class="form-control" name="account_name" value="{{ old('account_name') }}" required autocomplete="account_name" autofocus>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="account_number" class="col-md-4 col-form-label text-md-end">Account Name</label>
                            <div class="col-md-6">
                                <input id="account_number" type="text" class="form-control" name="account_number" value="{{ old('account_number') }}" required autocomplete="account_number" autofocus>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="bank_name" class="col-md-4 col-form-label text-md-end">Bank Name</label>
                            <div class="col-md-6">
                                <input id="bank_name" type="text" class="form-control" name="bank_name" value="{{ old('bank_name') }}" required autocomplete="bank_name" autofocus>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Submit
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
            $(document).on('submit', '#addBusiness', function(e) {
                e.preventDefault();
                if (!iti.isValidNumber()) {
                    Swal.fire({
                        title: "Error",
                        text: "Please enter a valid phone number.",
                        icon: "error"
                    });
                    return; // Stop form submission
                }
                let myform = document.getElementById("addBusiness");
                let fd = new FormData(myform);
                fd.append('full_number', iti.getNumber());
                $.ajax({
                    url: "{{ route('api.business.store') }}",
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
                        window.location.href = res.route;
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

            const input = document.querySelector("#phone");
            const iti = window.intlTelInput(input, {
                utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@23.8.1/build/js/utils.js",
            });

            $('#phone').on('keypress', function(e) {
                const keyCode = e.keyCode || e.which;
                const keyValue = String.fromCharCode(keyCode);
                const regex = /^[0-9+\-() ]+$/;

                if (!regex.test(keyValue)) {
                    e.preventDefault();
                    return false;
                }
            });

            $('#phone').on('change', function() {
                if (iti.isValidNumber()) {
                    $(this).removeClass('is-invalid');
                    $('#phoneError').text('');
                    $('#submitBtn').prop('disabled', false);
                } else {
                    $(this).addClass('is-invalid');
                    $('#submitBtn').prop('disabled', true);

                    const errorCode = iti.getValidationError();
                    let errorMessage = "Invalid phone number";

                    switch (errorCode) {
                        case 1:
                            errorMessage = "Invalid country code";
                            break;
                        case 2:
                            errorMessage = "Too short";
                            break;
                        case 3:
                            errorMessage = "Too long";
                            break;
                        case 4:
                            errorMessage = "Invalid number";
                            break;
                        default:
                            errorMessage = "Invalid phone number";
                    }

                    $('#phoneError').text(errorMessage);
                }
            });
        })
    </script>
@endsection