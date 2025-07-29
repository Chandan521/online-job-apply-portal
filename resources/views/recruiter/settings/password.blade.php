<!-- Password Tab -->
<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <h4 class="mb-4">Change Password</h4>
        <form action="{{ route('recruiter.password.update') }}" method="POST">
            @csrf
            @method('PUT')
            <!-- Current Password -->
            <div class="mb-3 position-relative">
                <label for="current_password" class="form-label fw-semibold">Current Password</label>
                <div class="input-group">
                    <input type="password" name="current_password" id="current_password"
                        class="form-control @error('current_password') is-invalid @enderror" required>
                    <span class="input-group-text bg-white border-start-0"
                        onclick="togglePassword('current_password', this)" style="cursor: pointer;">
                        <i class="fas fa-eye text-muted"></i>
                    </span>
                </div>
                @error('current_password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- New Password -->
            <div class="mb-3 position-relative">
                <label for="new_password" class="form-label fw-semibold">New Password</label>
                <div class="input-group">
                    <input type="password" name="password" id="new_password"
                        class="form-control @error('password') is-invalid @enderror" required>
                    <span class="input-group-text bg-white border-start-0"
                        onclick="togglePassword('new_password', this)" style="cursor: pointer;">
                        <i class="fas fa-eye text-muted"></i>
                    </span>
                </div>
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>


            <!-- Confirm New Password -->
            <div class="mb-3 position-relative">
                <label for="password_confirmation" class="form-label fw-semibold">Confirm New Password</label>
                <div class="input-group">
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                        required>
                    <span class="input-group-text bg-white border-start-0"
                        onclick="togglePassword('password_confirmation', this)" style="cursor: pointer;">
                        <i class="fas fa-eye text-muted"></i>
                    </span>
                </div>
            </div>


            <!-- Submit -->
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-success px-4">
                    <i class="fas fa-save me-2"></i>Update Password
                </button>
            </div>
        </form>

    </div>
</div>
