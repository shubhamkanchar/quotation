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
                            <label for="type" class="col-md-4 col-form-label text-md-end">Type</label>
                            <div class="col-md-6">
                                <select id="type" type="text" class="form-select" name="type" value="{{ old('type') }}" required autocomplete="type" autofocus>
                                    <option>Quotation</option>
                                    <option>invoice</option>
                                    <option>Purchase Order</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="terms" class="col-md-4 col-form-label text-md-end">Terms And Condition</label>
                            <div class="col-md-6">
                                <textarea id="terms" type="text" class="form-control" name="terms" value="{{ old('terms') }}" required autocomplete="terms" autofocus></textarea>
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
                        }).then(function() {
                            window.location.href = res.route;
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