<!-- Job Title -->
<div class="col-md-12 mb-3">
    <label class="form-label">Job Title</label>
    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<!-- Company -->
<div class="col-md-12 mb-3">
    <label class="form-label">Company</label>
    <input type="text" name="company" class="form-control @error('company') is-invalid @enderror" value="{{ old('company') }}" required>
    @error('company') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<!-- Location -->
<div class="col-md-12 mb-3">
    <label class="form-label">Location</label>
    <input type="text" name="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location') }}">
    @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<!-- Salary -->
<div class="col-md-12 mb-3">
    <label class="form-label">Salary (INR / Month)</label>
    <div class="d-flex gap-2">
        <div class="w-50">
            <select name="salary_from" class="form-select @error('salary_from') is-invalid @enderror">
                <option value="">From</option>
                @for($i = 0; $i <= 100000; $i += 5000)
                    <option value="{{ $i }}" {{ old('salary_from') == $i ? 'selected' : '' }}>₹{{ number_format($i) }}</option>
                @endfor
            </select>
            @error('salary_from') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>

        <div class="w-50">
            <select name="salary_to" class="form-select @error('salary_to') is-invalid @enderror">
                <option value="">To</option>
                @for($i = 10000; $i <= 200000; $i += 5000)
                    <option value="{{ $i }}" {{ old('salary_to') == $i ? 'selected' : '' }}>₹{{ number_format($i) }}</option>
                @endfor
            </select>
            @error('salary_to') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>
    </div>
</div>


<div class="col-md-12 mb-3">
    <div class="d-flex gap-4">
        <!-- Job Type -->
        <div class="w-50">
            <label class="form-label d-block">Job Type</label>
            <div class="d-flex flex-wrap gap-2">
                @foreach(['Full Time', 'Part Time', 'Contract', 'Internship'] as $type)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="type[]" value="{{ $type }}" id="type_{{ $loop->index }}"
                            {{ is_array(old('type')) && in_array($type, old('type')) ? 'checked' : '' }}>
                        <label class="form-check-label" for="type_{{ $loop->index }}">{{ $type }}</label>
                    </div>
                @endforeach
            </div>
            @error('type') <div class="text-danger small d-block mt-1">{{ $message }}</div> @enderror
        </div>

        <!-- Job Shift -->
        <div class="w-50">
            <label class="form-label d-block">Job Shift</label>
            <div class="d-flex flex-wrap gap-2">
                @foreach(['Morning', 'Day', 'Evening', 'Night', 'Rotational'] as $shift)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="shift[]" value="{{ $shift }}" id="shift_{{ $loop->index }}"
                            {{ is_array(old('shift')) && in_array($shift, old('shift')) ? 'checked' : '' }}>
                        <label class="form-check-label" for="shift_{{ $loop->index }}">{{ $shift }}</label>
                    </div>
                @endforeach
            </div>
            @error('shift') <div class="text-danger small d-block mt-1">{{ $message }}</div> @enderror
        </div>
    </div>
</div>



<!-- Industry -->
<div class="col-md-12 mb-3">
    <label class="form-label">Industry</label>
    <input type="text" name="industry" class="form-control @error('industry') is-invalid @enderror" value="{{ old('industry') }}">
    @error('industry') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<!-- Remote -->
<div class="col-md-12 mb-3">
    <label class="form-label">Remote</label>
    <select name="is_remote" class="form-select @error('is_remote') is-invalid @enderror">
        <option value="0" {{ old('is_remote') == '0' ? 'selected' : '' }}>No</option>
        <option value="1" {{ old('is_remote') == '1' ? 'selected' : '' }}>Yes</option>
    </select>
    @error('is_remote') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<!-- Employment Level -->
<div class="col-md-12 mb-3">
    <label class="form-label d-block">Employment Level</label>
    @foreach(['Entry-level', 'Mid-level', 'Senior-level', 'Executive-level'] as $level)
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="employment_level[]" value="{{ $level }}" id="level_{{ $loop->index }}"
                {{ is_array(old('employment_level')) && in_array($level, old('employment_level')) ? 'checked' : '' }}>
            <label class="form-check-label" for="level_{{ $loop->index }}">{{ $level }}</label>
        </div>
    @endforeach
    @error('employment_level') <div class="text-danger small d-block mt-1">{{ $message }}</div> @enderror
</div>
