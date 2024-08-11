@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6 h3">
                                {{ __('Delivery Note List') }}
                            </div>
                            <div class="col-md-6 text-end">
                                <a href="{{ route('make-delivery-note.create') }}" class="btn btn-primary">Add</a>
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
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script>
        $(document).ready(function() {
            $(document).on('click', '.delete-delivery-note', function() {
                let route =  "{{ route('make-delivery-note.destroy', ':id') }}".replace(':id', $(this).data('id'));
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
                        $.ajax({
                            url: route,
                            cache: false,
                            processData: false,
                            contentType: false,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(res) {
                                $('#deliverynote-table').DataTable().draw(false);
                                Swal.fire({
                                    title: "Success",
                                    text: res.message,
                                    icon: "success"
                                });
                            },
                            error: function(error) {
                                // console.log(error);
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
