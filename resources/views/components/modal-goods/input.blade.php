<div class="modal fade" id="inputModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Form Goods Received</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="input-form" action="{{ route('goods.received.store') }}" method="POST">
                <div class="modal-body">
                    @csrf
                    <div class="mb-3">
                        <label for="shipment_id" class="form-label">Shipment ID</label>
                        <input type="text" class="form-control" id="shipment_id" name="shipment_id" readonly
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="tracking_number" class="form-label">Tracking Number</label>
                        <input type="text" class="form-control" id="tracking_number_input" name="tracking_number"
                            required readonly>
                    </div>
                    <div class="mb-3">
                        <input type="hidden" class="form-control" id="propose_order" name="propose_order" required>
                    </div>
                    <div class="mb-3">
                        <input type="hidden" class="form-control" id="restock_order" name="restock_order" required>
                    </div>
                    <div class="input-container" id="input-container">

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
        console.log('Selamat datang jquery inputmodal!');

        $('#input-form').submit(function(e) {
            e.preventDefault();
            console.log('no refresh');

            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            var tracking_number = $('#tracking_number_input').val();
            var propose_order = $('#propose_order').val();
            var restock_order = $('#restock_order').val();
            var shipment_id = $('#shipment_id').val();
            var quantity = $('#quantity').val();
            var quantities = [];

            $('input[name^="quantity"]').each(function() {
                quantities.push($(this).val());
            });

            console.log('tracking_number', tracking_number);
            console.log('propose_order', propose_order);
            console.log('restock_order', restock_order);
            console.log('shipment_id', shipment_id);
            console.log('quantities', quantities);

            // console.log(tracking_number, propose_order, restock_order, shipment_id, quantities);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrf_token
                }
            });
            $.ajax({
                type: 'POST',
                url: $('#input-form').attr('action'),
                data: {
                    "tracking_number": tracking_number,
                    "propose_order": propose_order,
                    "restock_order": restock_order,
                    "shipment_id": shipment_id,
                    "quantity": quantities,
                    "quantities": quantities,
                    "_token": csrf_token
                },
                success: function(response) {
                    // console.log('success:', response);

                    if (response.success) {

                        setTimeout(() => {
                            window.location.href =
                                "{{ route('goods.received.index') }}";
                        }, 800);
                        $('#input-form').trigger('reset');
                        $('#inputModal').modal('hide');

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
                        // window.location.href = "{{ route('goods.received.index') }}";

                        $('#inputModal').modal('hide');
                    }
                    $('.text-danger').remove();

                    $.each(errors, function(key, value) {
                        console.log(key, value);
                        var errorElement = $('<span class="text-danger">' + value +
                            '</span>');
                        if (key === 'supplier') {
                            errorElement.insertAfter('select[name="' + key +
                                '"]'); // Untuk select supplier
                        } else {
                            errorElement.insertAfter('input[name="' + key +
                                '"]'); // Untuk input lainnya
                        }
                    });

                    $('#inputModal').modal('show');
                }
            })
        });

    });
</script>
