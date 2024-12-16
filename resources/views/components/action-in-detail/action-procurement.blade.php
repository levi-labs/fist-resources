<div class="row align-items-center justify-content-center">
    <div class="col-md-12 text-end">
        @if ($status === 'pending' || $status === 'resubmitted')
            <button type="button" class="btn btn-md btn-icon btn-primary" data-bs-toggle="modal"
                data-bs-target="#exampleModal">
                Approve
            </button>
            <button type="button" class="btn btn-md btn-icon btn-warning" data-bs-toggle="modal"
                data-bs-target="#resubmitModal">
                Resubmit
            </button>
        @endif
        {{-- <a href="{{ route('restock.inventory.approve', $params) }}" data-bs-toggle="tooltip" data-bs-placement="top"
            data-bs-original-title="Approve" aria-label="Approve" class="btn btn-md btn-icon btn-info">Approve</a> --}}

        <a href="{{ route('restock.inventory.rejected', $params) }}" data-bs-toggle="tooltip" data-bs-placement="top"
            data-bs-original-title="Reject" aria-label="Reject" class="btn btn-md btn-icon btn-danger">Reject</a>

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


@include('components.modal-procurement.approve', ['params' => $params])

@include('components.modal-procurement.resubmit', ['params' => $params])
