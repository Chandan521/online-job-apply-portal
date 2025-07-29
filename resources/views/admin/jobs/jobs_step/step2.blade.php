<h5 class="fw-bold mb-4 text-primary"><i class="bi bi-file-text-fill me-2"></i>Step 2: Job Details</h5>
<div class="row">
    <!-- Skills -->
    <div class="col-md-12 mb-3">
        <label class="form-label">Skills (comma separated)</label>
        <input type="text" name="skills" value="{{ old('skills') }}" class="form-control @error('skills') is-invalid @enderror" required>
        @error('skills') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <!-- Description -->
    <div class="col-md-12 mb-3">
        <label class="form-label">Job Description</label>
        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" required>{{ old('description') }}</textarea>
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <!-- Requirements -->
    <div class="col-md-12 mb-3">
        <label class="form-label">Requirements</label>
        <textarea name="requirements" class="form-control @error('requirements') is-invalid @enderror" rows="4" required>{{ old('requirements') }}</textarea>
        @error('requirements') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <!-- Responsibilities -->
    <div class="col-md-12 mb-3">
        <label class="form-label">Responsibilities</label>
        <textarea name="responsibilities" class="form-control @error('responsibilities') is-invalid @enderror" rows="4" required>{{ old('responsibilities') }}</textarea>
        @error('responsibilities') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <!-- Benefits -->
    <div class="col-md-12 mb-3">
        <label class="form-label">Benefits</label>
        <textarea name="benefits" class="form-control @error('benefits') is-invalid @enderror" rows="4">{{ old('benefits') }}</textarea>
        @error('benefits') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <!-- Application URL -->
    <div class="col-md-6 mb-3">
        <label class="form-label">Application URL</label>
        <input type="url" name="application_url" value="{{ old('application_url') }}" class="form-control @error('application_url') is-invalid @enderror">
        @error('application_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <!-- Deadline -->
    <div class="col-md-6 mb-3">
        <label class="form-label">Deadline</label>
        <input type="date" name="deadline" value="{{ old('deadline') }}" class="form-control @error('deadline') is-invalid @enderror">
        @error('deadline') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <!-- Experience -->
    <div class="col-md-6 mb-3">
        <label class="form-label">Experience</label>
        <input type="text" name="experience" value="{{ old('experience') }}" class="form-control @error('experience') is-invalid @enderror">
        @error('experience') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <!-- Education -->
    <div class="col-md-6 mb-3">
        <label class="form-label d-block">Education</label>
        @php
            $educationOptions = ['High School', 'Diploma', "Bachelor's Degree", "Master's Degree", 'PhD', 'Other'];
            $oldEducation = old('education', []);
        @endphp
        <div class="d-flex flex-wrap gap-3">
            @foreach ($educationOptions as $option)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="education[]" value="{{ $option }}"
                           id="education_{{ $loop->index }}" {{ in_array($option, $oldEducation) ? 'checked' : '' }}>
                    <label class="form-check-label" for="education_{{ $loop->index }}">
                        {{ $option }}
                    </label>
                </div>
            @endforeach
        </div>
        @error('education') <div class="text-danger small d-block mt-1">{{ $message }}</div> @enderror
    </div>
</div>
