<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="my-alert p-2" id="my-alert"></div>
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Notes</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>
            <form id="tracking-form" action="{{ route('goods.received.tracking') }}" method="POST">
                <div class="modal-body">
                    @csrf
                    <div class="mb-3">
                        <label for="tracking_number" class="form-label">Tracking Number</label>
                        <input type="text" class="form-control" id="tracking_number" name="tracking_number" required>
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
@include('components.modal-goods.input');
<script>
    // Inisialisasi Select2

    $(document).ready(function() {
        console.log('Selamat datang jquery tracking!');
        $('#tracking-form').submit(function(e) {
            e.preventDefault();
            console.log('no refresh');

            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            var tracking_number = $('#tracking_number').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrf_token
                }
            });
            $.ajax({
                type: 'POST',
                url: $('#tracking-form').attr('action'),
                data: {
                    "tracking_number": tracking_number,
                    "_token": csrf_token
                },
                success: function(response) {
                    console.log('success:', response);

                    if (response.success) {
                        const data = response.data;
                        const item = response.item;
                        $('#inputModal #shipment_id').val(data.id);
                        $('#inputModal #propose_order').val(data
                            .proposed_product_purchase_order_id);
                        $('#inputModal #restock_order').val(data.restock_purchase_order_id);
                        $('#inputModal #tracking_number_input').val(tracking_number);

                        response.items.forEach(item => {
                            let inputHtml =
                                `
                                 <div class="mb-3">
                                    <label for="quantity[${item.id}]" class="form-label">${item.name}</label>
                                    <input type="text" class="form-control" id="quantity[${item.id}]" name="quantity[${item.id}]" value="${item.quantity}" required>
                                 </div>
                                 `;
                            $('#inputModal #input-container').append(inputHtml);
                        });


                        $('#my-alert').removeClass('alert-danger').hide();
                        $('#tracking-form').trigger('reset');
                        $('#exampleModal').modal('hide');
                        $('#inputModal').modal('show');
                    }
                },
                error: function(xhr, status, error) {
                    var errors = xhr.responseJSON.error;
                    // console.log(xhr.responseJSON);
                    console.log('Error:',
                        errors);
                    if (xhr.status == 400) {
                        var errorMessage = xhr.responseJSON
                            .error; // Pesan error dari server
                        var alertHtml = `
                                        <div class="alert alert-danger" role="alert">
                                            ${errorMessage}
                                        </div>
                                        `;
                        $('.my-alert').html(alertHtml);
                    }
                    if (xhr.status == 404) {
                        var errorMessage = xhr.responseJSON
                            .error; // Pesan error dari server
                        var alertHtml = `
                                        <div class="alert alert-danger" role="alert">
                                            ${errorMessage}
                                        </div>
                                        `;
                        $('.my-alert').html(alertHtml);
                    }
                    if (xhr.status == 500) {
                        var errorMessage = xhr.responseJSON
                            .error; // Pesan error dari server
                        console.log(errorMessage);

                        // Menampilkan pesan error di dalam alert dengan class .my-alert
                        window.location.href =
                            "#";

                        $('#exampleModal').modal('hide');
                    }
                    $('.text-danger').remove();

                    $('#exampleModal').modal('show');
                }
            })
        });

    });
</script>
