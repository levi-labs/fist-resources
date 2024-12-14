<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Notes</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('restock.inventory.approvedetail', $params) }}" method="POST">
                <div class="modal-body">
                    @csrf
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Delivery Date</label>
                        <input type="date" class="form-control" id="exampleFormControlInput1" name="delivery_date">
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Supplier</label>
                        <select class="form-select" id="supplier_id" name="supplier_id">
                            <option selected="" disabled="">Select Supplier</option>
                            @foreach ($suppliers as $supplier)
                                <option {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}
                                    value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                        @error('supplier_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlTextarea1" class="form-label">Notes</label>
                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="reason"></textarea>
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
        console.log('Selamat datang di Select2!');

        $('.my-select2').select2({});

        $('.my-select2').on('select2:open', function(e) {
            // Mengubah style setelah dropdown terbuka
            $('.select2-selection').css({
                'padding': '0.375rem 0.75rem',
                'height': '40px',
                'border': '1px solid rgb(62, 91, 232)',
                'background-color': '#f8f9fa',
                'color': '#495057'
            });
        });

        $('.my-select2').on('select2:close', function(e) {
            // Mengubah style setelah dropdown tertutup
            $('.select2-selection').css({
                'padding': '0.375rem 0.75rem',
                'height': '40px',
                'border': '1px solid #ced4da',
                'background-color': '#fff',
                'color': '#ced4da',
            });
        });

        $('.my-select2').on('change', function() {
            // Menampilkan nilai yang dipilih di console
            console.log('Nilai yang dipilih: ' + $(this).val());
        });
    });
</script>
