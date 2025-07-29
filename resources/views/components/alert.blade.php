@php
    $toastId = uniqid('toast_');
@endphp
@if (session('success'))
    <div class="toast align-items-center text-bg-success border-0 show position-fixed bottom-0 end-0 m-4" id="{{ $toastId }}_success" role="alert" aria-live="assertive" aria-atomic="true" style="z-index: 1080; min-width: 320px;">
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
@endif
@if (session('error'))
    <div class="toast align-items-center text-bg-danger border-0 show position-fixed bottom-0 end-0 m-4" id="{{ $toastId }}_error" role="alert" aria-live="assertive" aria-atomic="true" style="z-index: 1080; min-width: 320px;">
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-x-circle-fill me-2"></i>{{ session('error') }}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
@endif
@if ($errors->any())
    <div class="toast align-items-center text-bg-danger border-0 show position-fixed bottom-0 end-0 m-4" id="{{ $toastId }}_errors" role="alert" aria-live="assertive" aria-atomic="true" style="z-index: 1080; min-width: 320px;">
        <div class="d-flex">
            <div class="toast-body">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
@endif
@if (session('message'))
    <div class="toast align-items-center text-bg-info border-0 show position-fixed bottom-0 end-0 m-4"
         id="{{ $toastId }}_info" role="alert" aria-live="assertive" aria-atomic="true"
         style="z-index: 1080; min-width: 320px;">
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-info-circle-fill me-2"></i>{{ session('message') }}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto"
                    data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
@endif
@if (session('status'))
    <div class="toast align-items-center text-bg-success border-0 show position-fixed bottom-0 end-0 m-4"
         id="session_status_toast" role="alert" aria-live="assertive" aria-atomic="true"
         style="z-index: 1080; min-width: 320px;">
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('status') }}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto"
                    data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
@endif

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var toastElList = [].slice.call(document.querySelectorAll('.toast'));
        toastElList.forEach(function (toastEl) {
            var toast = new bootstrap.Toast(toastEl, { delay: 4000 });
            toast.show();
        });
    });
</script>
@endpush

{{-- Testing Alert  --}}
{{-- @if (true)
    <div class="toast align-items-center text-bg-success border-0 show position-fixed bottom-0 end-0 m-4" id="{{ $toastId }}_success" role="alert" aria-live="assertive" aria-atomic="true" style="z-index: 1080; min-width: 320px;">
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-check-circle-fill me-2"></i>This IS Testing Alert
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
@endif --}}
