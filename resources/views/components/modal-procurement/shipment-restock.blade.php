<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Shipment</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="shipment-form" action="{{ route('shipment.store') }}" method="POST">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" class="form-control" id="type" name="type"
                        value="{{ $type }}">
                    <input type="hidden" class="form-control" id="id" name="id"
                        value="{{ $id }}">
                    <div class="mb-3">
                        <label for="courier" class="form-label">Courier</label>
                        <input type="text" class="form-control" id="courier" name="courier">
                    </div>
                    <div class="mb-3">
                        <label for="tracking_number" class="form-label">Tracking Number</label>
                        <input type="text" class="form-control" id="tracking_number" name="tracking_number">
                    </div>
                    <div class="mb-3">
                        <label for="shipment_date" class="form-label">Shipments Date</label>
                        <input type="date" class="form-control" id="shipment_date" name="shipment_date">
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes :</label>
                        <textarea class="form-control" id="notes" rows="3" name="notes"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    // Inisialisasi Select2

    $(document).ready(function() {
        console.log('Selamat datang jquery shipment!');
        $('#shipment-form').submit(function(e) {
            e.preventDefault();

            var type_shipment = $('#type').val();
            var id = $('#id').val();
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            var shipment_date = $('#shipment_date').val();
            var notes = $('#notes').val();
            var tracking_number = $('#tracking_number').val();
            var courier = $('#courier').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrf_token
                }
            });
            $.ajax({
                type: 'POST',
                url: $('#shipment-form').attr('action'),
                data: {
                    "id": id,
                    "type": type_shipment,
                    "shipment_date": shipment_date,
                    "notes": notes,
                    "tracking_number": tracking_number,
                    "courier": courier,
                    "_token": csrf_token
                },

                success: function(response) {
                    // console.log('success:', response);

                    if (response.success) {

                        setTimeout(() => {
                            window.location.href =
                                "{{ route('restock.purchase.index') }}";
                        }, 800);
                        $('#shipment-form').trigger('reset');
                        $('#exampleModal').modal('hide');

                        // var alertHtml =
                        //     '<div class="index-card alert alert-success alert-dismissible fade show" role="alert">' +
                        //     'Your request has been approved, successfully.' +
                        //     '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                        //     '<span aria-hidden="true">&times;</span>' +
                        //     '</button>' +
                        //     '</div>';

                        // $('.index-card').html(alertHtml).show();
                    }
                },
                error: function(xhr, status, error) {
                    var errors = xhr.responseJSON.error;
                    // console.log(xhr.responseJSON);
                    console.log('Error:',
                        errors);
                    if (xhr.status == 500) {
                        var errorMessage = xhr.responseJSON
                            .error; // Pesan error dari server

                        // Menampilkan pesan error di dalam alert dengan class .my-alert


                        $('#exampleModal').modal('hide');
                    }
                    $('.text-danger').remove();

                    $.each(errors, function(key, value) {
                        console.log(key, value);
                        var errorElement = $('<span class="text-danger">' + value +
                            '</span>');

                        errorElement.insertAfter('input[name="' + key +
                            '"]'); // Untuk input lainnya

                    });

                    $('#exampleModal').modal('show');
                }
            })
        });

    });
</script>
