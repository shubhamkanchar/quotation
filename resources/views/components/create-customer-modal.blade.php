<div class="modal modal-lg fade" id="createCustomerModal" wire:ignore.self tabindex="-1" aria-labelledby="createCustomerModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Select Customers</h1>
                <button type="button" class="btn-close" id="cCustomerModalClose" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-header h3">Add Customer</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('api.customer.store') }}" id="addBusiness">
                            @csrf
                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end">Name</label>
                                <div class="col-md-8">
                                    <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                </div>
                            </div>
                            <input type="hidden" name="token" id="token" value="{{ auth()?->user()?->createToken('api')->plainTextToken }}">
                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end">Company Name</label>
                                <div class="col-md-8">
                                    <input id="company_name" type="text" class="form-control" name="company_name" value="{{ old('company_name') }}" required autocomplete="company_name" autofocus>
                                </div>
                            </div>
        
                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>
                                <div class="col-md-8">
                                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autocomplete="email">
                                </div>
                            </div>
        
                            <div class="row mb-3">
                                <label for="phone" class="col-md-4 col-form-label text-md-end">{{ __('Phone Number') }}</label>
                                <div class="col-md-8">
                                    <input id="phone" type="tel" class="form-control " name="number"  value="{{ old('phone') }}" required autocomplete="phone">
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <label for="address1" class="col-md-4 col-form-label text-md-end">Address 1</label>
                                <div class="col-md-8">
                                    <input id="address1" type="text" class="form-control" name="address_1" value="{{ old('address1') }}" required autocomplete="address1" autofocus>
                                </div>
                            </div>
        
                            <div class="row mb-3">
                                <label for="address2" class="col-md-4 col-form-label text-md-end">Address 2</label>
                                <div class="col-md-8">
                                    <input id="address2" type="text" class="form-control" name="address_2" value="{{ old('address2') }}" required autocomplete="address2" autofocus>
                                </div>
                            </div>
        
        
                            <div class="row mb-3">
                                <label for="other_info" class="col-md-4 col-form-label text-md-end">Other Info</label>
                                <div class="col-md-8">
                                    <input id="other_info" type="text" class="form-control" name="other_info" value="{{ old('other_info') }}" required autocomplete="other_info" autofocus>
                                </div>
                            </div>
        
                            <div class="row mb-3">
                                <label for="gstin_number" class="col-md-4 col-form-label text-md-end">GSTIN Number</label>
                                <div class="col-md-8">
                                    <input id="gstin_number" type="text" class="form-control" name="gstin_number" value="{{ old('gstin_number') }}" required autocomplete="gstin_number" autofocus>
                                </div>
                            </div>
        
                            <div class="row mb-3">
                                <label for="state" class="col-md-4 col-form-label text-md-end">country</label>
                                <div class="col-md-8">
                                    <select name="country" id="country" class="form-select"></select>
                                </div>
                            </div>
        
                            <div class="row mb-3">
                                <label for="state" class="col-md-4 col-form-label text-md-end">State</label>
                                <div class="col-md-8">
                                    <select name="state" id="state" class="form-select"></select>
                                </div>
                            </div>
        
                            <div class="row mb-3">
                                <label for="shipping_address" class="col-md-4 col-form-label text-md-end">Shipping Address</label>
                                <div class="col-md-8">
                                    <textarea id="shipping_address" type="text" class="form-control" name="shipping_address" value="{{ old('shipping_address') }}" required autocomplete="shipping_address" autofocus></textarea>
                                </div>
                            </div>
        
                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
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
</div>

@section('script')
    <script type="module">
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
                    url: "{{ route('api.customer.store') }}",
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
                        }).then(function() {
                            document.getElementById('cCustomerModalClose').click();
                            document.querySelector('[data-bs-target="#addCustomerModal"]').click();
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

            $(document).on('hidden.bs.modal', '#createCustomerModal', function() {
                document.querySelector('[data-bs-target="#addCustomerModal"]').click();
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

            const countrySelect = document.getElementById('country');
            const stateSelect = document.getElementById('state');
            const defaultCountryCode = 'IN';  // ISO code for India
            const defaultStateCode = 'MH';    // ISO code for Maharashtra

             // Get all countries and populate the country dropdown
            const countries = Country.getAllCountries();
            countries.forEach(country => {
                let option = document.createElement('option');
                option.value = country.isoCode; 
                option.text = country.name;
                if (country.isoCode === defaultCountryCode) {
                    option.selected = true; // Set India as the default selected country
                }
                countrySelect.add(option);
            });

            // Populate states for the default country (India)
            let states = State.getStatesOfCountry(defaultCountryCode);
            states.forEach(state => {
                let option = document.createElement('option');
                option.value = state.isoCode; 
                option.text = state.name;
                if (state.isoCode === defaultStateCode) {
                    option.selected = true; // Set Maharashtra as the default selected state
                }
                stateSelect.add(option);
            });

            // Handle country selection to populate states dynamically
            countrySelect.addEventListener('change', function() {
                let selectedCountryCode = this.value;
                stateSelect.innerHTML = '<option value="">Select State</option>'; // Reset state dropdown

                let states = State.getStatesOfCountry(selectedCountryCode);
                states.forEach(state => {
                    let option = document.createElement('option');
                    option.value = state.isoCode; 
                    option.text = state.name;
                    stateSelect.add(option);
                });
            });
        })
    </script>
@endsection