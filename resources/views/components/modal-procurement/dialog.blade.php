<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Notes</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="approve-form" action="{{ route('restock.inventory.approvedetail', $params) }}" method="POST">
                <div class="modal-body">
                    @csrf
                    <div class="mb-3">
                        <label for="delivery_date" class="form-label">Delivery Date</label>
                        <input type="date" class="form-control" id="delivery_date" name="delivery_date">
                    </div>
                    <div class="mb-3">
                        <label for="supplier" class="form-label">Suppliers</label>

                        <select class="form-select" id="supplier" name="supplier">
                            <option selected disabled>Select Supplier</option>
                            @foreach ($suppliers as $supplier)
                                <option {{ old('supplier') == $supplier->id ? 'selected' : '' }}
                                    value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="reason" class="form-label">Reasons for restock :</label>
                        <textarea class="form-control" id="reason" rows="3" name="reason"></textarea>
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
        console.log('Selamat datang jquery!');
        $('#approve-form').submit(function(e) {
            e.preventDefault();
            console.log('no refresh');

            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            var delivery_date = $('#delivery_date').val();
            var supplier = $('#supplier').val();
            var reason = $('#reason').val();


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrf_token
                }
            });
            $.ajax({
                type: 'POST',
                url: "{{ route('restock.inventory.approvedetail', $params) }}",
                data: {
                    "delivery_date": delivery_date,
                    "supplier": supplier,
                    "reason": reason,
                    "_token": csrf_token
                },
                success: function(response) {
                    if (response.success) {
                        setTimeout(() => {
                            window.location.href =
                                "{{ route('restock.inventory.index') }}"
                        }, 800);
                        $('#approve-form').trigger('reset');
                        $('#exampleModal').modal('hide');

                        var alertHtml =
                            '<div class="index-card alert alert-success alert-dismissible fade show" role="alert">' +
                            'Your request has been approved, successfully.' +
                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                            '<span aria-hidden="true">&times;</span>' +
                            '</button>' +
                            '</div>';

                        $('.index-card').html(alertHtml).show();
                    }
                    console.log('sukses');

                },
                error: function(xhr, status, error) {
                    var errors = xhr.responseJSON.error;
                    // console.log(xhr.responseJSON);
                    console.log('Error:',
                        errors);
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

                    $('#exampleModal').modal('show');
                }
            })
        });

    });
</script>
