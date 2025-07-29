<div>
    <h5 class="fw-bold mb-3">Account Settings</h5>
    <form method="POST" action="{{ route('user.settings.account.update') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Notification Preferences</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="notify_jobs" id="notifyJobs" checked>
                <label class="form-check-label" for="notifyJobs">Job Alerts</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="notify_reviews" id="notifyReviews">
                <label class="form-check-label" for="notifyReviews">Review Notifications</label>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Save Preferences</button>
    </form>
    <hr>
    <div class="mb-4 mt-5">
        <h5 class="text-danger">Delete Your Account</h5>
        <p class="text-muted">
            Deleting your account is <strong>permanent</strong>. All your data, applications, and saved items will be
            permanently removed and cannot be recovered.
        </p>
    </div>
    <form method="POST" action="{{ route('user.settings.account.delete') }}">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-outline-danger">Delete My Account</button>
    </form>
    <hr>
    <div class="mb-4">
        <h5>Deactivate Your Account</h5>
        <p class="text-muted">
            Deactivating your account will log you out and make your profile invisible to others.
            To reactivate your account in the future, please contact our customer support team.
        </p>

    </div>
    <form action="{{ route('user.account.deactivate') }}" method="POST"
        onsubmit="return confirm('Are you sure you want to deactivate your account?')">
        @csrf
        <button type="submit" class="btn btn-outline-danger">
            Deactivate Account
        </button>
    </form>

</div>
