@foreach ($appliedJobs ?? [] as $index => $job)
    @php
        $modalUpdateId = 'updateStatusModal_' . $index;
        $modalManageId = 'manageAppModal_' . $index;
        $status = $job->status ?? ['label' => 'N/A', 'class' => 'bg-secondary'];
    @endphp

    <!-- Update Status Modal -->
    <div class="modal fade" id="{{ $modalUpdateId }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-3">
            <div class="modal-header border-bottom">
                <div>
                    <h5 class="modal-title mb-0 fw-bold">Update your application status</h5>
                    <small class="text-muted d-block mt-1">
                        <i class="bi bi-eye-slash me-1"></i> Employers won’t see this
                    </small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex align-items-center py-3 px-4 hover-bg-light cursor-pointer">
                        <i class="bi bi-calendar-check-fill text-success fs-5 me-3"></i>
                        <span class="fw-semibold">Interviewing</span>
                    </li>
                    <li class="list-group-item d-flex align-items-center py-3 px-4 hover-bg-light cursor-pointer">
                        <i class="bi bi-hand-thumbs-up-fill text-success fs-5 me-3"></i>
                        <span class="fw-semibold">Offer received</span>
                    </li>
                    <li class="list-group-item d-flex align-items-center py-3 px-4 hover-bg-light cursor-pointer">
                        <i class="bi bi-person-check-fill text-success fs-5 me-3"></i>
                        <span class="fw-semibold">Hired</span>
                    </li>
                    <li class="list-group-item d-flex align-items-center py-3 px-4 hover-bg-light cursor-pointer">
                        <i class="bi bi-x-circle-fill text-danger fs-5 me-3"></i>
                        <span class="fw-semibold">Not selected by employer</span>
                    </li>
                    <li class="list-group-item d-flex align-items-center py-3 px-4 hover-bg-light cursor-pointer">
                        <i class="bi bi-chat-dots-fill text-danger fs-5 me-3"></i>
                        <span class="fw-semibold">No longer interested</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>


    {{-- Manage Modals Partial --}}
    <!-- Manage Application Modal -->
    <div class="modal fade" id="{{ $modalManageId }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Manage this application</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Here you can archive or delete this application.</p>
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-danger btn-sm me-2" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">Delete</button>
                        <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#archiveModal">Archive</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirm Delete Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this application? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Archive Modal -->
    <div class="modal fade" id="archiveModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Archive</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to archive this application? You can view archived applications in your profile.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Archive</button>
                </div>
            </div>
        </div>
    </div>
@endforeach
