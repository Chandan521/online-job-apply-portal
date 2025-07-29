<div>
    <h5 class="fw-bold mb-3">Security Settings</h5>
    <form method="POST" action="{{ route('user.settings.security.update') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Current Password</label>
            <input type="password" name="current_password" class="form-control mb-2" required>
        </div>
        <div class="mb-3">
            <label class="form-label">New Password</label>
            <input type="password" name="new_password" class="form-control mb-2" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Confirm New Password</label>
            <input type="password" name="new_password_confirmation" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Password</button>
    </form>
    <hr>
    <h6>Two-Factor Authentication</h6>
    <div class="alert alert-info">Coming soon: Enable 2FA for extra security.</div>
</div>
