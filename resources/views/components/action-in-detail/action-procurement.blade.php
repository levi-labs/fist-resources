<div class="row align-items-center justify-content-center">
    <div class="col-md-12 text-end">
        @if ($status === 'pending')
            <button type="button" class="btn btn-md btn-icon btn-primary" data-bs-toggle="modal"
                data-bs-target="#exampleModal">
                Approve
            </button>
        @endif
        {{-- <a href="{{ route('restock.inventory.approve', $params) }}" data-bs-toggle="tooltip" data-bs-placement="top"
            data-bs-original-title="Approve" aria-label="Approve" class="btn btn-md btn-icon btn-info">Approve</a> --}}
        <button type="button" class="btn btn-md btn-icon btn-warning" data-bs-toggle="modal"
            data-bs-target="#exampleModal">
            Resubmit
        </button>
        <a href="{{ route('restock.inventory.reject', $params) }}" data-bs-toggle="tooltip" data-bs-placement="top"
            data-bs-original-title="Reject" aria-label="Reject" class="btn btn-md btn-icon btn-danger">Reject</a>

    </div>
</div>

@include('components.modal-procurement.dialog', ['params' => $params])
