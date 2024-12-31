<div class="modal fade" id="checkModal" tabindex="-1" aria-labelledby="checkModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Shipment History</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div
                            class="iq-timeline0 m-0 d-flex align-items-center justify-content-between position-relative">
                            <ul class="list-inline p-0 m-0">
                                @isset($tracking_status)
                                    @foreach ($tracking_status as $item)
                                        <li>
                                            <div class="timeline-dots timeline-dot1 border-primary text-primary"></div>
                                            <h6 class="float-left mb-1">{{ $item['status'] }}</h6>
                                            <p class="mb-0">{{ $item['invoice_number'] }}</p>
                                            <small class="float-right mt-1">{{ $item['time'] }}</small>
                                            {{-- <div class="d-inline-block w-100">
                                       <p>{{ $item['notes'] }}</p>
                                   </div> --}}
                                        </li>
                                    @endforeach
                                @endisset

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // Inisialisasi Select2
</script>
