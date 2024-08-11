@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6 h3">
                                {{ __('Customer') }}
                            </div>
                            <div class="col-md-6 text-end">
                                <a href="{{ route('customer.create') }}" class="btn btn-primary">Add</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{ $dataTable->table() }}
                    </div>
                    <form id="deleteCustomer">
                        <input type="hidden" name="token" id="token" value="{{ auth()?->user()?->createToken('api')->plainTextToken }}">
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script>
        $(document).ready(function() {
            $(document).on('click', '.delete-customer', function() {
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        let myform = document.getElementById("deleteCustomer");
                        let fd = new FormData(myform);
                        $.ajax({
                            url: "{{ url('api/customer') }}/"+$(this).data('id'),
                            data: fd,
                            cache: false,
                            processData: false,
                            contentType: false,
                            type: 'DELETE',
                            headers: {
                                'Authorization': 'Bearer ' + $('#token').val()
                            },
                            success: function(res) {
                                Swal.fire({
                                    title: "Success",
                                    text: res.message,
                                    icon: "success"
                                });
                                $('#customers-table').DataTable().draw(false);
                            },
                            error: function(error) {
                                console.log(error);
                                Swal.fire({
                                    title: "Error",
                                    text: error.responseJSON.message,
                                    icon: "error"
                                });
                            }
                        })
                    }
                });
            })
        });
    </script>
@endsection
