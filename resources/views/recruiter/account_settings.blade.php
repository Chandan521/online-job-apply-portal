@extends('recruiter.layout.app')

@section('title', 'Account Settings')
@push('account_settings_style')
    <style>
    .list-group-item-action.active {
        background-color: #0d6efd !important;
        color: #fff !important;
        font-weight: 500;
    }

    .list-group-item-action:hover {
        background-color: #f8f9fa;
    }

    .list-group-item i {
        color: #0d6efd;
    }

    .list-group-item.active i {
        color: #fff !important;
    }

    .list-group-item.text-danger i {
        color: #dc3545;
    }

    .list-group-item.text-danger.active {
        background-color: #dc3545 !important;
    }

    label.form-label {
        font-weight: 600;
    }

    .form-control,
    .form-select,
    textarea {
        border-radius: 0.375rem;
        box-shadow: none;
    }

    .position-relative label[for="profile_photo"]:hover {
        background-color: #f1f1f1;
        transition: 0.3s;
    }
</style>

@endpush


@section('content')
<div class="container mt-5 pt-5">
    <h2 class="mb-4">Account Settings</h2>
    <div class="row mt-4">
        <!-- Sidebar -->
        <div class="col-md-3 mb-4">
            <div class="list-group shadow-sm rounded overflow-hidden" id="settings-tabs" role="tablist">
                <a class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3 active"
                    id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile">
                    <i class="fas fa-user-circle fs-5 text-primary"></i> <span>Profile</span>
                </a>
                <a class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3"
                    id="password-tab" data-bs-toggle="tab" href="#password" role="tab" aria-controls="password">
                    <i class="fas fa-lock fs-5 text-warning"></i> <span>Change Password</span>
                </a>
                <a class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3"
                    id="device-tab" data-bs-toggle="tab" href="#device" role="tab" aria-controls="device">
                    <i class="fas fa-laptop fs-5 text-success"></i> <span>Device Management</span>
                </a>
                <a class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3"
                    id="email-tab" data-bs-toggle="tab" href="#email" role="tab" aria-controls="email">
                    <i class="fas fa-envelope fs-5 text-success"></i> <span>Email Preferences</span>
                </a>
                <a class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3"
                    id="notifications-tab" data-bs-toggle="tab" href="#notifications" role="tab"
                    aria-controls="notifications">
                    <i class="fas fa-bell fs-5 text-info"></i> <span>Notifications</span>
                </a>
                <a class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3 text-danger"
                    id="delete-tab" data-bs-toggle="tab" href="#delete" role="tab" aria-controls="delete">
                    <i class="fas fa-trash-alt fs-5"></i> <span>Delete Account</span>
                </a>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="col-md-9">
            <div class="tab-content" id="settings-tabContent">
                <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    @include('recruiter.settings.profile')
                </div>
                <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                    @include('recruiter.settings.password')
                </div>
                <div class="tab-pane fade" id="device" role="tabpanel" aria-labelledby="device-tab">
                    @include('recruiter.settings.device')
                </div>
                <div class="tab-pane fade" id="email" role="tabpanel" aria-labelledby="email-tab">
                    Email Tab
                </div>
                <div class="tab-pane fade" id="notifications" role="tabpanel" aria-labelledby="notifications-tab">
                    @include('recruiter.settings.notification')
                </div>
                <div class="tab-pane fade" id="delete" role="tabpanel" aria-labelledby="delete-tab">
                    Delete Tab
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('footer')
    <footer class="text-center text-muted py-4 mt-auto border-top small bg-white">
        <div class="container">
            <div class="row justify-content-center mb-2">
                <div class="col-md-auto">
                    <strong class="text-dark">{{ setting('site_name' ?? 'Name Not Set') }}</strong> &copy; {{ date('Y') }}. All
                    rights reserved.
                </div>
                <div class="col-md-auto">
                    <span class="d-block d-md-inline mt-2 mt-md-0">
                        | Empowering recruiters to find the right talent with confidence.
                    </span>
                </div>
            </div>
            <div class="small text-secondary">
                <span class="d-block d-md-inline">Need help? Visit our <a href="{{ route('pages.show', 'help') }}"
                        class="text-decoration-none text-primary">Employer Help Center</a>.</span>
            </div>
        </div>
    </footer>
@endsection

@push('account_settings_script')
    <script>
        function previewAvatar(event) {
            const reader = new FileReader();
            reader.onload = function() {
                document.getElementById('avatarPreview').src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }

        document.addEventListener("DOMContentLoaded", function() {
            const hash = window.location.hash;
            const defaultTab = document.querySelector('#settings-tabs a[href="#profile"]');

            if (hash && document.querySelector(`#settings-tabs a[href="${hash}"]`)) {
                const tab = new bootstrap.Tab(document.querySelector(`#settings-tabs a[href="${hash}"]`));
                tab.show();
            } else if (defaultTab) {
                const tab = new bootstrap.Tab(defaultTab);
                tab.show();
            }

            document.querySelectorAll('#settings-tabs a[data-bs-toggle="tab"]').forEach(tab => {
                tab.addEventListener('shown.bs.tab', function(e) {
                    history.replaceState(null, null, e.target.getAttribute("href"));
                });
            });
        });

        // Password Tab 
        function togglePassword(inputId, el) {
            const input = document.getElementById(inputId);
            const icon = el.querySelector('i');

            if (!input) {
                console.warn(`Input with ID "${inputId}" not found`);
                return;
            }

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>
@endpush
