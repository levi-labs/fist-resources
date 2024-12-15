<div class="modal fade" id="resubmitModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Notes</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="approve-form" action="{{ route('restock.inventory.resubmit', $params) }}" method="POST">
                <div class="modal-body">
                    @method('PUT')
                    @csrf
                    <div class="mb-3">
                        <label for="reason" class="form-label">Reasons for resubmit :</label>
                        <textarea class="form-control" id="reason" rows="3" name="reason" placeholder="Reasons for resubmit..."></textarea>
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
        $('#resubmit-form').submit(function(e) {
            e.preventDefault();
            console.log('no refresh');

            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            var reason = $('#reason').val();


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrf_token
                }
            });
            $.ajax({
                type: 'PUT',
                url: "{{ route('restock.inventory.resubmit', $params) }}",
                data: {
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
                },
                error: function(xhr, status, error) {
                    var errors = xhr.responseJSON.error;
                    // console.log(xhr.responseJSON);

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

                    $('#resubmitModal').modal('show');
                }
            })
        });

    });
</script>
