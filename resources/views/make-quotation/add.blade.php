@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header h3">Add Terms</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('api.term.store') }}" id="addTerms">
                        @csrf
                        
                        <input type="hidden" name="token" id="token" value="{{ auth()?->user()?->createToken('api')->plainTextToken }}">
                        <div class="row mb-3">
                            <label for="to" class="col-md-4 col-form-label text-md-end">TO</label>
                            <div class="col-md-6">
                                <select id="to" type="text" class="form-select single-select2 select2" name="to" required autocomplete="to" autofocus>
                                    <option selected disabled>Please select customer</option>
                                    @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }} - {{ $customer->company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="products" class="col-md-4 col-form-label text-md-end">Products</label>
                            <div class="col-md-6">
                                <select id="products" type="text" class="form-select single-select2 select2" name="products" required autocomplete="to" autofocus>
                                    <option selected disabled>Please select product</option>
                                    @foreach($products as $product)
                                    <option>{{ $product->product_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="offset-md-4 col-md-6">
                                <ul id="items">
                                    
                                </ul>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="terms" class="col-md-4 col-form-label text-md-end">Terms and Condition</label>
                            <div class="col-md-6">
                                <select id="terms" type="text" class="form-select single-select2 select2" name="terms" required autocomplete="to" autofocus>
                                    <option selected disabled>Please select terms and Condition</option>
                                    @foreach($terms as $terms)
                                    <option>{{ $terms->terms }}</option>
                                    @endforeach
                                </select>
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
            $(document).on('submit', '#addTerms', function(e) {
                e.preventDefault();
                let myform = document.getElementById("addTerms");
                let fd = new FormData(myform);
                $.ajax({
                    url: "{{ route('api.term.store') }}",
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

            var el = document.getElementById('items');
            var sortable = Sortable.create(el);
        })
    </script>
@endsection